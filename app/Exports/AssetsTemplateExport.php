<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AssetsTemplateExport implements WithHeadings, FromArray, ShouldAutoSize, WithStyles
{
    public function headings(): array
    {
        return [
            'ID Único',
            'Nombre del Activo',
            'Especificaciones',
            'Cantidad',
            'Valor ($)',
            'Fecha de Compra (AAAA-MM-DD)',
            'Estado (active/maintenance/decommissioned)',
            'Ubicación',
            'Categoría',
            'Subcategoría',
            'Proveedor',
            'Placa Municipio',
            'Notas'
        ];
    }

    public function array(): array
    {
        return [
            // Example 1 - Computer
            [
                'ACT-001',
                'Laptop Dell Latitude 5420',
                'Intel Core i5-1135G7, 16GB RAM, 512GB SSD, Windows 11 Pro',
                '1',
                '1250000',
                '2024-01-15',
                'active',
                'Oficina Principal',
                'Tecnología',
                'Computadoras',
                'Dell Colombia',
                '',
                'Asignada al departamento de IT'
            ],
            // Example 2 - Furniture
            [
                'ACT-002',
                'Escritorio Ejecutivo',
                'Madera MDF, 1.60m x 0.80m, color nogal',
                '5',
                '450000',
                '2024-02-20',
                'active',
                'Sala de Juntas',
                'Mobiliario',
                'Escritorios',
                'Muebles & Diseño',
                '',
                'Para sala de reuniones'
            ],
            // Example 3 - Vehicle
            [
                'ACT-003',
                'Camioneta Toyota Hilux',
                '4x4, Diesel, 2.8L, Doble Cabina, Blanca',
                '1',
                '95000000',
                '2023-06-10',
                'active',
                'Almacén General',
                'Vehículos',
                'Camionetas',
                'Toyota Colombia',
                'ABC-123',
                'Vehículo de carga'
            ],
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row (headers) as bold
            1 => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }
}
