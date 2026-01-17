<?php
namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;

class BackupController extends Controller
{
    public function index()
    {
        $backupPath = storage_path('app/backups');
        $backups = [];
        
        if (file_exists($backupPath)) {
            $files = \File::files($backupPath);
            
            foreach ($files as $file) {
                $backups[] = [
                    'name' => $file->getFilename(),
                    'size' => $this->formatBytes($file->getSize()),
                    'date' => date('Y-m-d H:i:s', $file->getMTime()),
                    'path' => $file->getPathname(),
                ];
            }
            
            // Sort by date descending
            usort($backups, function($a, $b) {
                return strtotime($b['date']) - strtotime($a['date']);
            });
        }
        
        return view('superadmin.backups.index', compact('backups'));
    }
    
    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);
        return round($bytes, $precision) . ' ' . $units[$pow];
    }
    
    public function download($filename)
    {
        $filepath = storage_path('app/backups/' . $filename);
        
        if (!file_exists($filepath)) {
            return redirect()->back()->with('error', 'Backup no encontrado.');
        }
        
        return response()->download($filepath);
    }
    
    public function create($companyId)
    {
        try {
            $company = \App\Models\Company::findOrFail($companyId);
            
            // Use the artisan command for portable backup
            \Artisan::call('backup:company', [
                'company_id' => $companyId,
                '--full' => true
            ]);
            
            return redirect()->back()->with('success', 'Backup (incluyendo registro de empresa) creado exitosamente.');
            
        } catch (\Exception $e) {
            \Log::error('Backup exception', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Error al crear backup: ' . $e->getMessage());
        }
    }
    
    public function delete($filename)
    {
        try {
            $filepath = storage_path('app/backups/' . $filename);
            
            if (!file_exists($filepath)) {
                return redirect()->back()->with('error', 'Backup no encontrado.');
            }
            
            unlink($filepath);
            \Log::info('Backup deleted', ['file' => $filename]);
            
            return redirect()->back()->with('success', 'Backup eliminado exitosamente.');
            
        } catch (\Exception $e) {
            \Log::error('Backup delete exception', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Error al eliminar backup: ' . $e->getMessage());
        }
    }
    
    public function restore($filename)
    {
        try {
            $filepath = storage_path('app/backups/' . $filename);
            
            if (!file_exists($filepath)) {
                return redirect()->back()->with('error', 'Backup no encontrado.');
            }
            
            $sql = file_get_contents($filepath);
            if (empty($sql)) {
                return redirect()->back()->with('error', 'El archivo de backup está vacío.');
            }

            // Superadmins can restore any backup without company check constraints
            
            $driver = \DB::getDriverName();
            
            if ($driver === 'mysql') {
                \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            } elseif ($driver === 'sqlite') {
                \DB::statement('PRAGMA foreign_keys = OFF;');
            }
            
            // Split SQL into individual statements more safely
            $statements = preg_split('/;\s*$/m', $sql);
            
            $statements = array_filter(
                array_map('trim', $statements),
                function($statement) {
                    return !empty($statement) && !str_starts_with($statement, '--') && !str_starts_with($statement, '/*');
                }
            );

            $executedCount = 0;
            foreach ($statements as $statement) {
                if (!empty($statement)) {
                    try {
                        \DB::statement($statement);
                        $executedCount++;
                    } catch (\Exception $e) {
                        \Log::warning("Error executing statement in superadmin restore: " . $e->getMessage());
                    }
                }
            }
            
            if ($driver === 'mysql') {
                \DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            } elseif ($driver === 'sqlite') {
                \DB::statement('PRAGMA foreign_keys = ON;');
            }

            if ($executedCount === 0) {
                return redirect()->back()->with('error', 'El archivo no contenía instrucciones válidas para procesar.');
            }

            return redirect()->back()->with('success', "Backup restaurado exitosamente. Se procesaron {$executedCount} instrucciones.");
            
        } catch (\Exception $e) {
            \Log::error('Backup restore exception', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Error al restaurar backup: ' . $e->getMessage());
        }
    }
    
    public function upload(\Illuminate\Http\Request $request)
    {
        try {
            $request->validate([
                'backup_file' => 'required|file|mimes:sql,zip|max:51200', // 50MB max
            ]);
            
            $file = $request->file('backup_file');
            $filename = 'uploaded-' . date('Y-m-d-His') . '-' . $file->getClientOriginalName();
            
            // Ensure backups directory exists
            $backupPath = storage_path('app/backups');
            if (!file_exists($backupPath)) {
                mkdir($backupPath, 0755, true);
            }
            
            // Move file to backups directory
            $file->move($backupPath, $filename);
            
            \Log::info('Backup uploaded', ['file' => $filename]);
            
            return redirect()->back()->with('success', 'Backup subido exitosamente: ' . $filename);
            
        } catch (\Exception $e) {
            \Log::error('Backup upload exception', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Error al subir backup: ' . $e->getMessage());
        }
    }
}
