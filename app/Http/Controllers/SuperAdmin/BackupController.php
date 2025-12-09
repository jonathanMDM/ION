<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

class BackupController extends Controller
{
    public function index()
    {
        $backupDir = storage_path('app/backups');
        $backups = [];

        if (file_exists($backupDir)) {
            $files = scandir($backupDir);
            foreach ($files as $file) {
                if ($file !== '.' && $file !== '..' && pathinfo($file, PATHINFO_EXTENSION) === 'sql') {
                    $backups[] = [
                        'name' => $file,
                        'size' => filesize("{$backupDir}/{$file}"),
                        'date' => filemtime("{$backupDir}/{$file}"),
                    ];
                }
            }
            
            // Sort by date descending
            usort($backups, function($a, $b) {
                return $b['date'] - $a['date'];
            });
        }

        return view('superadmin.backups.index', compact('backups'));
    }

    public function create(Company $company)
    {
        try {
            // Superadmins create full backups (with --full option to include company record)
            Artisan::call('backup:company', [
                'company_id' => $company->id,
                '--full' => true
            ]);
            
            return back()->with('success', "Backup completo creado exitosamente para {$company->name}");
        } catch (\Exception $e) {
            return back()->with('error', 'Error al crear el backup: ' . $e->getMessage());
        }
    }

    public function download($filename)
    {
        $filepath = storage_path("app/backups/{$filename}");
        
        if (!file_exists($filepath)) {
            return back()->with('error', 'Archivo de backup no encontrado.');
        }

        return response()->download($filepath);
    }

    public function delete($filename)
    {
        $filepath = storage_path("app/backups/{$filename}");
        
        if (file_exists($filepath)) {
            unlink($filepath);
            return back()->with('success', 'Backup eliminado exitosamente.');
        }

        return back()->with('error', 'Archivo de backup no encontrado.');
    }

    public function upload(Request $request)
    {
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

            // Detect database driver
            $driver = \DB::getDriverName();
            
            // Disable foreign key checks based on database type
            if ($driver === 'mysql') {
                \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            } elseif ($driver === 'sqlite') {
                \DB::statement('PRAGMA foreign_keys = OFF;');
            }
            
            // Split SQL into individual statements
            $statements = array_filter(
                array_map('trim', explode(';', $sql)),
                function($statement) {
                    return !empty($statement) && !str_starts_with($statement, '--');
                }
            );

            // Execute each statement
            foreach ($statements as $statement) {
                if (!empty($statement)) {
                    try {
                        \DB::statement($statement);
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

            return back()->with('success', 'Backup restaurado exitosamente. Los datos han sido recuperados.');
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
}
