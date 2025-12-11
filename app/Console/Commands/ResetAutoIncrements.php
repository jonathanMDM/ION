<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ResetAutoIncrements extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:reset-ids {--table= : Specific table to reset}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset auto-increment IDs for specified tables (locations, categories, subcategories)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $table = $this->option('table');
        
        if ($table) {
            $this->resetTable($table);
        } else {
            // Reset all tables
            $this->info('Reiniciando IDs de todas las tablas...');
            $this->resetTable('locations');
            $this->resetTable('categories');
            $this->resetTable('subcategories');
            $this->info('✅ Todos los IDs han sido reiniciados exitosamente!');
        }
        
        return 0;
    }
    
    private function resetTable($table)
    {
        try {
            // Get the maximum ID currently in the table
            $maxId = DB::table($table)->max('id');
            $nextId = $maxId ? $maxId + 1 : 1;
            
            // Reset the auto-increment value
            DB::statement("ALTER TABLE {$table} AUTO_INCREMENT = {$nextId}");
            
            $this->info("✓ Tabla '{$table}': Auto-increment ajustado a {$nextId}");
        } catch (\Exception $e) {
            $this->error("✗ Error al reiniciar tabla '{$table}': " . $e->getMessage());
        }
    }
}
