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
            ['name' => 'Oficina Principal', 'company_id' => $companyId],
            ['name' => 'Almacén General', 'company_id' => $companyId],
            ['name' => 'Sala de Servidores', 'company_id' => $companyId],
            ['name' => 'Recepción', 'company_id' => $companyId],
            ['name' => 'Sala de Juntas', 'company_id' => $companyId],
            ['name' => 'Departamento de IT', 'company_id' => $companyId],
            ['name' => 'Recursos Humanos', 'company_id' => $companyId],
            ['name' => 'Contabilidad', 'company_id' => $companyId],
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
                'Computadoras',
                'Servidores',
                'Redes',
                'Periféricos',
                'Telefonía',
            ],
            'Mobiliario' => [
                'Escritorios',
                'Sillas',
                'Archivadores',
                'Estanterías',
                'Mesas de Juntas',
            ],
            'Vehículos' => [
                'Automóviles',
                'Camionetas',
                'Motocicletas',
                'Camiones',
            ],
            'Equipos de Oficina' => [
                'Impresoras',
                'Fotocopiadoras',
                'Proyectores',
                'Escáneres',
                'Trituradoras',
            ],
            'Herramientas' => [
                'Herramientas Eléctricas',
                'Herramientas Manuales',
                'Equipos de Medición',
                'Equipos de Seguridad',
            ],
            'Electrodomésticos' => [
                'Refrigeradores',
                'Microondas',
                'Cafeteras',
                'Aires Acondicionados',
            ],
        ];

        foreach ($categoriesData as $categoryName => $subcategories) {
            $category = Category::firstOrCreate(
                ['name' => $categoryName, 'company_id' => $companyId],
                ['name' => $categoryName, 'company_id' => $companyId]
            );

            foreach ($subcategories as $subcategoryName) {
                Subcategory::firstOrCreate(
                    [
                        'name' => $subcategoryName,
                        'category_id' => $category->id,
                        'company_id' => $companyId
                    ],
                    [
                        'name' => $subcategoryName,
                        'category_id' => $category->id,
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
