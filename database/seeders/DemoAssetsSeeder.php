<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Asset;
use App\Models\Company;
use App\Models\Location;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Supplier;

class DemoAssetsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the first company (or create one if doesn't exist)
        $company = Company::first();
        
        if (!$company) {
            echo "No hay empresas registradas. Por favor crea una empresa primero.\n";
            return;
        }

        // Get or create locations
        $locations = [
            'Oficina Principal' => Location::firstOrCreate(
                ['name' => 'Oficina Principal', 'company_id' => $company->id],
                ['address' => 'Calle 100 #15-20, Bogotá']
            ),
            'Bodega Central' => Location::firstOrCreate(
                ['name' => 'Bodega Central', 'company_id' => $company->id],
                ['address' => 'Carrera 68 #25-10, Bogotá']
            ),
            'Sucursal Norte' => Location::firstOrCreate(
                ['name' => 'Sucursal Norte', 'company_id' => $company->id],
                ['address' => 'Calle 170 #45-30, Bogotá']
            ),
        ];

        // Get or create categories and subcategories
        $categories = [
            'Computadores' => Category::firstOrCreate(
                ['name' => 'Computadores', 'company_id' => $company->id]
            ),
            'Muebles' => Category::firstOrCreate(
                ['name' => 'Muebles', 'company_id' => $company->id]
            ),
            'Equipos de Oficina' => Category::firstOrCreate(
                ['name' => 'Equipos de Oficina', 'company_id' => $company->id]
            ),
            'Vehículos' => Category::firstOrCreate(
                ['name' => 'Vehículos', 'company_id' => $company->id]
            ),
        ];

        $subcategories = [
            'Laptops' => Subcategory::firstOrCreate(
                ['name' => 'Laptops', 'category_id' => $categories['Computadores']->id]
            ),
            'Monitores' => Subcategory::firstOrCreate(
                ['name' => 'Monitores', 'category_id' => $categories['Computadores']->id]
            ),
            'Escritorios' => Subcategory::firstOrCreate(
                ['name' => 'Escritorios', 'category_id' => $categories['Muebles']->id]
            ),
            'Sillas' => Subcategory::firstOrCreate(
                ['name' => 'Sillas', 'category_id' => $categories['Muebles']->id]
            ),
            'Impresoras' => Subcategory::firstOrCreate(
                ['name' => 'Impresoras', 'category_id' => $categories['Equipos de Oficina']->id]
            ),
            'Automóviles' => Subcategory::firstOrCreate(
                ['name' => 'Automóviles', 'category_id' => $categories['Vehículos']->id]
            ),
        ];

        // Get or create suppliers
        $suppliers = [
            'Dell Colombia' => Supplier::firstOrCreate(
                ['name' => 'Dell Colombia', 'company_id' => $company->id],
                ['email' => 'ventas@dell.com.co', 'phone' => '601-5555555']
            ),
            'HP Colombia' => Supplier::firstOrCreate(
                ['name' => 'HP Colombia', 'company_id' => $company->id],
                ['email' => 'info@hp.com.co', 'phone' => '601-4444444']
            ),
            'Muebles & Diseño' => Supplier::firstOrCreate(
                ['name' => 'Muebles & Diseño', 'company_id' => $company->id],
                ['email' => 'ventas@mueblesydiseno.com', 'phone' => '601-3333333']
            ),
        ];

        // Create demo assets
        $demoAssets = [
            [
                'custom_id' => 'LAP-001',
                'name' => 'Laptop Dell Latitude 5420',
                'subcategory_id' => $subcategories['Laptops']->id,
                'location_id' => $locations['Oficina Principal']->id,
                'supplier_id' => $suppliers['Dell Colombia']->id,
                'value' => 3500000,
                'quantity' => 1,
                'model' => 'Latitude 5420',
                'serial_number' => 'DL5420-2024-001',
                'status' => 'active',
                'purchase_date' => '2024-01-15',
                'specifications' => 'Intel Core i7-1185G7, 16GB RAM, 512GB SSD, Pantalla 14" FHD',
                'next_maintenance_date' => '2026-04-15',
                'maintenance_frequency_days' => 90,
            ],
            [
                'custom_id' => 'LAP-002',
                'name' => 'Laptop HP EliteBook 840 G8',
                'subcategory_id' => $subcategories['Laptops']->id,
                'location_id' => $locations['Oficina Principal']->id,
                'supplier_id' => $suppliers['HP Colombia']->id,
                'value' => 4200000,
                'quantity' => 1,
                'model' => 'EliteBook 840 G8',
                'serial_number' => 'HP840-2024-002',
                'status' => 'active',
                'purchase_date' => '2024-02-20',
                'specifications' => 'Intel Core i7-1165G7, 32GB RAM, 1TB SSD, Pantalla 14" FHD Touch',
                'next_maintenance_date' => '2026-05-20',
                'maintenance_frequency_days' => 90,
            ],
            [
                'custom_id' => 'MON-001',
                'name' => 'Monitor Dell UltraSharp 27"',
                'subcategory_id' => $subcategories['Monitores']->id,
                'location_id' => $locations['Oficina Principal']->id,
                'supplier_id' => $suppliers['Dell Colombia']->id,
                'value' => 1200000,
                'quantity' => 5,
                'minimum_quantity' => 2,
                'model' => 'U2720Q',
                'serial_number' => 'DLU27-2024-001',
                'status' => 'active',
                'purchase_date' => '2024-03-10',
                'specifications' => '27 pulgadas, 4K UHD (3840x2160), IPS, USB-C',
            ],
            [
                'custom_id' => 'ESC-001',
                'name' => 'Escritorio Ejecutivo Moderno',
                'subcategory_id' => $subcategories['Escritorios']->id,
                'location_id' => $locations['Oficina Principal']->id,
                'supplier_id' => $suppliers['Muebles & Diseño']->id,
                'value' => 850000,
                'quantity' => 10,
                'minimum_quantity' => 3,
                'model' => 'Executive Pro 160',
                'status' => 'active',
                'purchase_date' => '2023-11-05',
                'specifications' => 'Madera MDF, 160cm x 80cm, Color Nogal, 3 cajones',
            ],
            [
                'custom_id' => 'SIL-001',
                'name' => 'Silla Ergonómica Ejecutiva',
                'subcategory_id' => $subcategories['Sillas']->id,
                'location_id' => $locations['Oficina Principal']->id,
                'supplier_id' => $suppliers['Muebles & Diseño']->id,
                'value' => 450000,
                'quantity' => 15,
                'minimum_quantity' => 5,
                'model' => 'ErgoMax Pro',
                'status' => 'active',
                'purchase_date' => '2023-11-05',
                'specifications' => 'Respaldo en malla, Ajuste lumbar, Brazos 4D, Base cromada',
            ],
            [
                'custom_id' => 'IMP-001',
                'name' => 'Impresora Multifuncional HP LaserJet',
                'subcategory_id' => $subcategories['Impresoras']->id,
                'location_id' => $locations['Oficina Principal']->id,
                'supplier_id' => $suppliers['HP Colombia']->id,
                'value' => 2100000,
                'quantity' => 2,
                'minimum_quantity' => 1,
                'model' => 'LaserJet Pro MFP M428fdw',
                'serial_number' => 'HPLJ428-2024-001',
                'status' => 'active',
                'purchase_date' => '2024-01-20',
                'specifications' => 'Impresión, Copia, Escaneo, Fax, WiFi, Dúplex automático, 40ppm',
                'next_maintenance_date' => '2026-03-20',
                'maintenance_frequency_days' => 60,
            ],
            [
                'custom_id' => 'LAP-003',
                'name' => 'Laptop Dell Inspiron 15',
                'subcategory_id' => $subcategories['Laptops']->id,
                'location_id' => $locations['Sucursal Norte']->id,
                'supplier_id' => $suppliers['Dell Colombia']->id,
                'value' => 2200000,
                'quantity' => 1,
                'model' => 'Inspiron 15 3520',
                'serial_number' => 'DLI15-2024-003',
                'status' => 'maintenance',
                'purchase_date' => '2023-08-15',
                'specifications' => 'Intel Core i5-1235U, 8GB RAM, 256GB SSD, Pantalla 15.6" FHD',
            ],
            [
                'custom_id' => 'MON-002',
                'name' => 'Monitor HP 24"',
                'subcategory_id' => $subcategories['Monitores']->id,
                'location_id' => $locations['Bodega Central']->id,
                'supplier_id' => $suppliers['HP Colombia']->id,
                'value' => 650000,
                'quantity' => 3,
                'model' => 'HP 24mh',
                'status' => 'active',
                'purchase_date' => '2023-12-01',
                'specifications' => '24 pulgadas, Full HD (1920x1080), IPS, HDMI',
            ],
        ];

        foreach ($demoAssets as $assetData) {
            $assetData['company_id'] = $company->id;
            
            Asset::updateOrCreate(
                ['custom_id' => $assetData['custom_id'], 'company_id' => $company->id],
                $assetData
            );
        }

        echo "✅ Se han creado " . count($demoAssets) . " activos de demostración para la empresa: {$company->name}\n";
        echo "📋 Tipos de activos creados:\n";
        echo "   - 3 Laptops (Dell y HP)\n";
        echo "   - 2 Monitores (Dell y HP)\n";
        echo "   - 1 Escritorio\n";
        echo "   - 1 Silla ergonómica\n";
        echo "   - 1 Impresora multifuncional\n";
        echo "\n";
        echo "🎯 Ahora puedes tomar capturas de pantalla para el manual.\n";
    }
}
