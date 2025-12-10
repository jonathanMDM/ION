<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Database\Seeders\ExampleDataSeeder;

class SeedExampleData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:seed-examples {company_id? : ID de la empresa (opcional)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Crea datos de ejemplo para ubicaciones, categorías y subcategorías';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $companyId = $this->argument('company_id');
        
        if ($companyId) {
            $company = \App\Models\Company::find($companyId);
            if (!$company) {
                $this->error("❌ Empresa con ID {$companyId} no encontrada");
                return 1;
            }
            $this->info("📦 Creando datos de ejemplo para: {$company->name}");
        } else {
            $this->info("📦 Creando datos de ejemplo para la primera empresa...");
        }

        $this->call('db:seed', ['--class' => ExampleDataSeeder::class]);
        
        $this->newLine();
        $this->info('✅ ¡Datos de ejemplo creados exitosamente!');
        $this->info('');
        $this->info('📍 Ubicaciones incluidas:');
        $this->line('   • Oficina Principal');
        $this->line('   • Almacén General');
        $this->line('   • Sala de Servidores');
        $this->line('   • Y más...');
        $this->info('');
        $this->info('📁 Categorías incluidas:');
        $this->line('   • Tecnología (Computadoras, Servidores, Redes, etc.)');
        $this->line('   • Mobiliario (Escritorios, Sillas, Archivadores, etc.)');
        $this->line('   • Vehículos (Automóviles, Camionetas, etc.)');
        $this->line('   • Equipos de Oficina (Impresoras, Proyectores, etc.)');
        $this->line('   • Herramientas');
        $this->line('   • Electrodomésticos');
        
        return 0;
    }
}
