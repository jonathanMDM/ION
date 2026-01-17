<?php

namespace App\Console\Commands;

use App\Models\Company;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class BackupCompany extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backup:company {company_id} {--full : Include company record for full restoration}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a backup of a specific company\'s data';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $companyId = $this->argument('company_id');
        $company = Company::find($companyId);

        if (!$company) {
            $this->error("Company with ID {$companyId} not found.");
            return 1;
        }

        $this->info("Starting backup for company: {$company->name}");

        // Create backup directory if it doesn't exist
        $backupDir = storage_path('app/backups');
        if (!file_exists($backupDir)) {
            mkdir($backupDir, 0755, true);
        }

        $timestamp = now()->format('Y-m-d_His');
        $filename = "company_{$companyId}_{$company->name}_{$timestamp}.sql";
        $filepath = "{$backupDir}/{$filename}";

        // Tables to backup
        // Include 'companies' table only if --full option is used (Superadmin backups)
        $tables = [];
        
        if ($this->option('full')) {
            $tables[] = 'companies';  // Full backup includes company record
        }
        
        // Add data tables (always included)
        $tables = array_merge($tables, [
            'users',
            'assets',
            'locations',
            'categories',
            'subcategories',
            'suppliers',
            'employees',
            'maintenances',
            'asset_movements',
            'asset_assignments',
            'custom_fields',
            'field_visibilities',
            'user_notifications',
            'support_tickets',
            'support_ticket_notes',
        ]);

        $sqlContent = "-- ION BACKUP METADATA\n";
        $sqlContent .= "-- VERSION: 1.0\n";
        $sqlContent .= "-- COMPANY_ID: {$companyId}\n";
        $sqlContent .= "-- COMPANY_NAME: {$company->name}\n";
        $sqlContent .= "-- CREATED_AT: " . now()->toDateTimeString() . "\n";
        $sqlContent .= "-- END METADATA\n\n";
        
        // Add database-agnostic foreign key disable
        $sqlContent .= "-- Disable foreign key checks\n";

        foreach ($tables as $table) {
            $this->info("Backing up table: {$table}");
            
            $query = DB::table($table);
            $deleteQuery = "";

            if ($table === 'companies') {
                $records = $query->where('id', $companyId)->get();
                $deleteQuery = "DELETE FROM `{$table}` WHERE `id` = {$companyId};\n";
            } elseif (in_array($table, ['user_notifications'])) {
                $records = $query->whereIn('user_id', function($q) use ($companyId) {
                    $q->select('id')->from('users')->where('company_id', $companyId);
                })->get();
                // For restoration, we need a way to delete these. Since they don't have company_id, 
                // we'll use same subquery logic in the SQL file.
                $deleteQuery = "DELETE FROM `{$table}` WHERE `user_id` IN (SELECT `id` FROM `users` WHERE `company_id` = {$companyId});\n";
            } elseif (in_array($table, ['asset_assignments'])) {
                $records = $query->whereIn('asset_id', function($q) use ($companyId) {
                    $q->select('id')->from('assets')->where('company_id', $companyId);
                })->get();
                $deleteQuery = "DELETE FROM `{$table}` WHERE `asset_id` IN (SELECT `id` FROM `assets` WHERE `company_id` = {$companyId});\n";
            } elseif (in_array($table, ['support_ticket_notes'])) {
                $records = $query->whereIn('support_ticket_id', function($q) use ($companyId) {
                    $q->select('id')->from('support_tickets')->where('company_id', $companyId);
                })->get();
                $deleteQuery = "DELETE FROM `{$table}` WHERE `support_ticket_id` IN (SELECT `id` FROM `support_tickets` WHERE `company_id` = {$companyId});\n";
            } else {
                $records = $query->where('company_id', $companyId)->get();
                $deleteQuery = "DELETE FROM `{$table}` WHERE `company_id` = {$companyId};\n";
            }
            
            if ($records->isEmpty()) {
                continue;
            }

            $sqlContent .= "-- Table: {$table}\n";
            $sqlContent .= $deleteQuery;
            
            foreach ($records as $record) {
                $columns = array_keys((array) $record);
                $values = array_values((array) $record);
                
                // Escape values
                $escapedValues = array_map(function($value) {
                    if (is_null($value)) {
                        return 'NULL';
                    }
                    return "'" . addslashes($value) . "'";
                }, $values);
                
                $sqlContent .= "INSERT INTO `{$table}` (`" . implode('`, `', $columns) . "`) VALUES (" . implode(', ', $escapedValues) . ");\n";
            }
            
            $sqlContent .= "\n";
        }

        // Add database-agnostic foreign key enable
        $sqlContent .= "-- Re-enable foreign key checks\n";
        $sqlContent .= "-- For MySQL: SET FOREIGN_KEY_CHECKS=1;\n";
        $sqlContent .= "-- For SQLite: PRAGMA foreign_keys = ON;\n";

        // Save to file
        file_put_contents($filepath, $sqlContent);

        $this->info("Backup completed successfully!");
        $this->info("File saved to: {$filepath}");
        $this->info("File size: " . number_format(filesize($filepath) / 1024, 2) . " KB");

        return 0;
    }
}
