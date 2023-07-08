<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class ProductsExport implements FromCollection, WithHeadings, ShouldAutoSize, WithDrawings, WithHeadingRow, WithEvents
{
    use Exportable;
    public function headings(): array
    {
        return ['ID', 'Nombre', 'Descripción', 'Precio', 'Stock', 'Calificación', 'Status', 'Codigo de barras', 'Imagen', 'Fecha de creación', 'Ultima modificación'];
    }

    public function collection()
    {
        return Product::all();
    }

    public function drawings(): array
    {
        return $this->collection()->map(function ($product, $index) {
            $drawing = new Drawing();
            $drawing->setPath(public_path('/storage/' . $product->image));
            $drawing->setHeight(90);
            $drawing->setCoordinates('I' . ($index + 2));

            return $drawing;
        })->toArray();
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $totalRows = $event->sheet->getDelegate()->getHighestRow();
                $event->sheet->getDelegate()->getRowDimension(1)->setRowHeight(60);
                $event->sheet->getDelegate()->getStyle('A1:L1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 14,
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ]);
                for ($row = 2; $row <= $totalRows; $row++) {
                    $event->sheet->getDelegate()->getRowDimension($row)->setRowHeight(90);
                    $event->sheet->getDelegate()->getStyle($row)->applyFromArray([
                        'alignment' => [
                            'horizontal' => Alignment::HORIZONTAL_CENTER,
                            'vertical' => Alignment::VERTICAL_CENTER,
                        ],
                    ]);
                }
            },
        ];
    }
}
