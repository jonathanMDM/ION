<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Location;
use App\Models\Category;
use App\Models\Subcategory;

class ExampleDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the first company (or you can specify a company_id)
        $companyId = \App\Models\Company::first()->id ?? 1;

        // Locations (Ubicaciones)
        $locations = [
            ['name' => 'Oficina Principal', 'description' => 'Edificio administrativo central', 'company_id' => $companyId],
            ['name' => 'Almacén General', 'description' => 'Bodega de almacenamiento principal', 'company_id' => $companyId],
            ['name' => 'Sala de Servidores', 'description' => 'Data center y equipos de red', 'company_id' => $companyId],
            ['name' => 'Recepción', 'description' => 'Área de recepción y atención al público', 'company_id' => $companyId],
            ['name' => 'Sala de Juntas', 'description' => 'Sala de reuniones ejecutivas', 'company_id' => $companyId],
            ['name' => 'Departamento de IT', 'description' => 'Área de tecnología e informática', 'company_id' => $companyId],
            ['name' => 'Recursos Humanos', 'description' => 'Departamento de personal', 'company_id' => $companyId],
            ['name' => 'Contabilidad', 'description' => 'Departamento financiero', 'company_id' => $companyId],
        ];

        foreach ($locations as $location) {
            Location::firstOrCreate(
                ['name' => $location['name'], 'company_id' => $companyId],
                $location
            );
        }

        // Categories and Subcategories
        $categoriesData = [
            'Tecnología' => [
                'Computadoras' => 'Equipos de cómputo de escritorio y portátiles',
                'Servidores' => 'Equipos servidores y almacenamiento',
                'Redes' => 'Switches, routers y equipos de red',
                'Periféricos' => 'Monitores, teclados, mouse, impresoras',
                'Telefonía' => 'Teléfonos IP y equipos de comunicación',
            ],
            'Mobiliario' => [
                'Escritorios' => 'Mesas y escritorios de trabajo',
                'Sillas' => 'Sillas de oficina y visitantes',
                'Archivadores' => 'Gabinetes y archivadores',
                'Estanterías' => 'Estantes y repisas',
                'Mesas de Juntas' => 'Mesas para salas de reuniones',
            ],
            'Vehículos' => [
                'Automóviles' => 'Vehículos livianos',
                'Camionetas' => 'Vehículos de carga liviana',
                'Motocicletas' => 'Motos y ciclomotores',
                'Camiones' => 'Vehículos de carga pesada',
            ],
            'Equipos de Oficina' => [
                'Impresoras' => 'Impresoras láser e inyección de tinta',
                'Fotocopiadoras' => 'Equipos multifuncionales',
                'Proyectores' => 'Proyectores y pantallas',
                'Escáneres' => 'Equipos de digitalización',
                'Trituradoras' => 'Destructoras de documentos',
            ],
            'Herramientas' => [
                'Herramientas Eléctricas' => 'Taladros, sierras, etc.',
                'Herramientas Manuales' => 'Martillos, destornilladores, etc.',
                'Equipos de Medición' => 'Multímetros, niveles, etc.',
                'Equipos de Seguridad' => 'Cascos, guantes, etc.',
            ],
            'Electrodomésticos' => [
                'Refrigeradores' => 'Neveras y congeladores',
                'Microondas' => 'Hornos microondas',
                'Cafeteras' => 'Máquinas de café',
                'Aires Acondicionados' => 'Equipos de climatización',
            ],
        ];

        foreach ($categoriesData as $categoryName => $subcategories) {
            $category = Category::firstOrCreate(
                ['name' => $categoryName, 'company_id' => $companyId],
                ['name' => $categoryName, 'company_id' => $companyId]
            );

            foreach ($subcategories as $subcategoryName => $description) {
                Subcategory::firstOrCreate(
                    [
                        'name' => $subcategoryName,
                        'category_id' => $category->id,
                        'company_id' => $companyId
                    ],
                    [
                        'name' => $subcategoryName,
                        'category_id' => $category->id,
                        'description' => $description,
                        'company_id' => $companyId
                    ]
                );
            }
        }

        $this->command->info('✅ Datos de ejemplo creados exitosamente!');
        $this->command->info('📍 Ubicaciones: ' . count($locations));
        $this->command->info('📁 Categorías: ' . count($categoriesData));
        $this->command->info('📂 Subcategorías: ' . array_sum(array_map('count', $categoriesData)));
    }
}
