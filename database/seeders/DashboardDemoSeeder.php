<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Location;
use App\Models\Asset;
use App\Models\Supplier;

class DashboardDemoSeeder extends Seeder
{
    public function run(): void
    {
        // Create Categories and Subcategories
        $categories = [
            'Tecnología' => ['Computadoras', 'Impresoras', 'Servidores'],
            'Mobiliario' => ['Escritorios', 'Sillas', 'Archivadores'],
            'Vehículos' => ['Autos', 'Camionetas'],
        ];

        foreach ($categories as $categoryName => $subcategories) {
            $category = Category::firstOrCreate(['name' => $categoryName]);
            
            foreach ($subcategories as $subName) {
                Subcategory::firstOrCreate([
                    'name' => $subName,
                    'category_id' => $category->id
                ]);
            }
        }

        // Create Locations
        $locations = ['Oficina Central', 'Sucursal Norte', 'Almacén', 'Bodega'];
        foreach ($locations as $locationName) {
            Location::firstOrCreate(['name' => $locationName]);
        }

        // Create a Supplier
        $supplier = Supplier::firstOrCreate([
            'name' => 'Proveedor Demo',
            'email' => 'demo@proveedor.com'
        ]);

        // Create some demo assets
        $subcategory = Subcategory::first();
        $location = Location::first();

        if ($subcategory && $location) {
            for ($i = 1; $i <= 10; $i++) {
                Asset::create([
                    'name' => 'Activo Demo ' . $i,
                    'custom_id' => 'DEMO-' . str_pad($i, 4, '0', STR_PAD_LEFT),
                    'subcategory_id' => Subcategory::inRandomOrder()->first()->id,
                    'location_id' => Location::inRandomOrder()->first()->id,
                    'supplier_id' => $supplier->id,
                    'status' => ['active', 'maintenance', 'decommissioned'][array_rand(['active', 'maintenance', 'decommissioned'])],
                    'value' => rand(100, 5000),
                    'purchase_date' => now()->subDays(rand(1, 365)),
                ]);
            }
        }

        $this->command->info('Demo data created successfully!');
    }
}
