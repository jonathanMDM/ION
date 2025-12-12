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
        
        if ($this->confirm('⚠️ ESTO BORRARÁ TODOS LOS DATOS de las tablas seleccionadas para reiniciar los IDs a 1. ¿Deseas continuar?')) {
            if ($table) {
                $this->resetTable($table);
            } else {
                // Reset all tables in correct order to avoid FK constraints
                $this->info('Reiniciando IDs de todas las tablas...');
                
                // Child tables first
                $this->resetTable('assets');
                $this->resetTable('subcategories');
                
                // Parent tables
                $this->resetTable('locations');
                $this->resetTable('categories');
                $this->resetTable('suppliers');
                
                $this->info('✅ Todos los IDs han sido reiniciados a 1!');
            }
        }
        
        return 0;
    }
    
    private function resetTable($table)
    {
        try {
            $driver = DB::connection()->getDriverName();

            if ($driver === 'pgsql') {
                DB::statement("TRUNCATE TABLE {$table} RESTART IDENTITY CASCADE");
            } else {
                // MySQL content
                DB::statement("SET FOREIGN_KEY_CHECKS=0;");
                DB::table($table)->truncate();
                DB::statement("SET FOREIGN_KEY_CHECKS=1;");
            }
            
            $this->info("✓ Tabla '{$table}': Datos borrados y ID reiniciado a 1.");
        } catch (\Exception $e) {
            $this->error("✗ Error al reiniciar tabla '{$table}': " . $e->getMessage());
        }
    }
}
