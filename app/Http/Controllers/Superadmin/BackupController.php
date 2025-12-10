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
            
            // Create backup filename
            $filename = 'backup-' . $company->name . '-' . date('Y-m-d-His') . '.sql';
            $filepath = storage_path('app/backups/' . $filename);
            
            // Ensure backups directory exists
            if (!file_exists(storage_path('app/backups'))) {
                mkdir(storage_path('app/backups'), 0755, true);
            }
            
            // Get database connection details
            $dbHost = config('database.connections.pgsql.host');
            $dbPort = config('database.connections.pgsql.port');
            $dbName = config('database.connections.pgsql.database');
            $dbUser = config('database.connections.pgsql.username');
            $dbPass = config('database.connections.pgsql.password');
            
            // Build pg_dump command
            $command = sprintf(
                'PGPASSWORD=%s pg_dump -h %s -p %s -U %s -d %s --clean --if-exists > %s 2>&1',
                escapeshellarg($dbPass),
                escapeshellarg($dbHost),
                escapeshellarg($dbPort),
                escapeshellarg($dbUser),
                escapeshellarg($dbName),
                escapeshellarg($filepath)
            );
            
            // Execute backup
            exec($command, $output, $returnVar);
            
            if ($returnVar === 0 && file_exists($filepath)) {
                \Log::info('Backup created successfully', ['company_id' => $companyId, 'file' => $filename]);
                return redirect()->back()->with('success', 'Backup creado exitosamente: ' . $filename);
            } else {
                \Log::error('Backup failed', ['company_id' => $companyId, 'output' => $output, 'return' => $returnVar]);
                return redirect()->back()->with('error', 'Error al crear el backup. Esta funcionalidad requiere pg_dump instalado en el servidor.');
            }
            
        } catch (\Exception $e) {
            \Log::error('Backup exception', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Error al crear backup: ' . $e->getMessage());
        }
    }
}
