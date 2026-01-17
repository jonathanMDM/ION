<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Spatie\Backup\BackupDestination\BackupDestination;
use Spatie\Backup\Tasks\Monitor\BackupDestinationStatusFactory;

class BackupController extends Controller
{
    public function index()
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'No autorizado');
        }
        
        $backupDir = storage_path('app/backups');
        $backups = [];

        if (file_exists($backupDir)) {
            $files = scandir($backupDir);
            $companyId = Auth::user()->company_id;
            
            foreach ($files as $file) {
                if ($file !== '.' && $file !== '..' && pathinfo($file, PATHINFO_EXTENSION) === 'sql') {
                    // Only show backups for this company
                    if (str_contains($file, "company_{$companyId}_")) {
                        $backups[] = [
                            'name' => $file,
                            'size' => filesize("{$backupDir}/{$file}"),
                            'date' => filemtime("{$backupDir}/{$file}"),
                        ];
                    }
                }
            }
            
            // Sort by date descending
            usort($backups, function($a, $b) {
                return $b['date'] - $a['date'];
            });
        }

        return view('backups.index', compact('backups'));
    }
    
    
    public function create()
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'No autorizado');
        }
        
        try {
            $companyId = Auth::user()->company_id;
            // Company admins create data-only backups (without --full option)
            Artisan::call('backup:company', ['company_id' => $companyId]);
            return back()->with('success', 'Backup de datos creado exitosamente.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al crear backup: ' . $e->getMessage());
        }
    }
    
    
    public function download($filename)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'No autorizado');
        }
        
        $filepath = storage_path("app/backups/{$filename}");
        
        if (!file_exists($filepath)) {
            return back()->with('error', 'Archivo no encontrado.');
        }
        
        // Verify this backup belongs to user's company
        $companyId = Auth::user()->company_id;
        if (!str_contains($filename, "company_{$companyId}_")) {
            abort(403, 'No autorizado para descargar este backup.');
        }
        
        return response()->download($filepath);
    }
    
    
    public function delete($filename)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'No autorizado');
        }
        
        $filepath = storage_path("app/backups/{$filename}");
        
        // Verify this backup belongs to user's company
        $companyId = Auth::user()->company_id;
        if (!str_contains($filename, "company_{$companyId}_")) {
            abort(403, 'No autorizado para eliminar este backup.');
        }
        
        if (file_exists($filepath)) {
            unlink($filepath);
            return back()->with('success', 'Backup eliminado exitosamente.');
        }
        
        return back()->with('error', 'Archivo no encontrado.');
    }
    
    
    public function upload(Request $request)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'No autorizado');
        }

        $request->validate([
            'backup_file' => 'required|file|mimes:sql,zip|max:51200', // 50MB max
        ]);

        try {
            $file = $request->file('backup_file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $backupDir = storage_path('app/backups');

            // Create backups directory if it doesn't exist
            if (!file_exists($backupDir)) {
                mkdir($backupDir, 0755, true);
            }

            // Move the uploaded file
            $file->move($backupDir, $filename);

            return back()->with('success', 'Backup subido exitosamente: ' . $filename);
        } catch (\Exception $e) {
            return back()->with('error', 'Error al subir el backup: ' . $e->getMessage());
        }
    }

    public function restore($filename)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'No autorizado');
        }

        $filepath = storage_path("app/backups/{$filename}");
        
        if (!file_exists($filepath)) {
            return back()->with('error', 'Archivo de backup no encontrado.');
        }

        try {
            // Read SQL file
            $sql = file_get_contents($filepath);
            
            if (empty($sql)) {
                return back()->with('error', 'El archivo de backup está vacío.');
            }

            // Get company ID from authenticated user
            $companyId = Auth::user()->company_id;

            // Verify that this backup belongs to the user's company using headers
            $hasHeader = preg_match('/-- COMPANY_ID: (\d+)/', $sql, $matches);
            $backupCompanyId = $hasHeader ? $matches[1] : null;

            if (!$hasHeader || (int)$backupCompanyId !== (int)$companyId) {
                // Fallback to old check for backward compatibility
                if (!str_contains($sql, "company_id` = {$companyId}") && !str_contains($sql, "`id` = {$companyId}") && !str_contains($sql, "({$companyId},") && !str_contains($sql, ", {$companyId},") && !str_contains($sql, ", {$companyId})")) {
                    return back()->with('error', 'Este backup no pertenece a tu empresa o el formato no es válido.');
                }
            }

            // Detect database driver
            $driver = \DB::getDriverName();
            
            // Disable foreign key checks based on database type
            if ($driver === 'mysql') {
                \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            } elseif ($driver === 'sqlite') {
                \DB::statement('PRAGMA foreign_keys = OFF;');
            }
            
            // Split SQL into individual statements more safely
            // Using a simple regex to split by semicolon at the end of lines
            $statements = preg_split('/;\s*$/m', $sql);
            
            $statements = array_filter(
                array_map('trim', $statements),
                function($statement) {
                    return !empty($statement) && !str_starts_with($statement, '--') && !str_starts_with($statement, '/*');
                }
            );

            $executedCount = 0;
            // Execute each statement
            foreach ($statements as $statement) {
                if (!empty($statement)) {
                    try {
                        \DB::statement($statement);
                        $executedCount++;
                    } catch (\Exception $e) {
                        // Log the error but continue with other statements
                        \Log::warning("Error executing statement: " . $e->getMessage());
                    }
                }
            }
            
            // Re-enable foreign key checks based on database type
            if ($driver === 'mysql') {
                \DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            } elseif ($driver === 'sqlite') {
                \DB::statement('PRAGMA foreign_keys = ON;');
            }

            if ($executedCount === 0) {
                return back()->with('error', 'El archivo no contenía datos válidos para tu empresa.');
            }

            return back()->with('success', "Backup restaurado exitosamente. Se han recuperado {$executedCount} registros/instrucciones.");
        } catch (\Exception $e) {
            // Re-enable foreign key checks in case of error
            $driver = \DB::getDriverName();
            if ($driver === 'mysql') {
                \DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            } elseif ($driver === 'sqlite') {
                \DB::statement('PRAGMA foreign_keys = ON;');
            }
            
            return back()->with('error', 'Error al restaurar el backup: ' . $e->getMessage());
        }
    }

    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes > 1024; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, $precision) . ' ' . $units[$i];
    }
}
