<?php

namespace App\Exports;

use App\Models\Product;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class ProductsExport implements
    FromCollection,
    WithHeadings,
    ShouldAutoSize,
    WithHeadingRow,
    WithEvents,
    ShouldQueue
{
    use Exportable;
    public function headings(): array
    {
        return [
            'ID',
            'Nombre',
            'Descripción',
            'Precio',
            'Stock',
            'Calificación',
            'Status',
            'Código de barras',
            'Imagen',
            'Fecha de creación',
            'Ultima modificación',
        ];
    }

    public function collection(): Collection
    {
        return Product::all()->map(function ($product) {
            $product->image = config('app.url') . Storage::url($product->image);

            return $product;
        });
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
                        'size' => 7,
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
