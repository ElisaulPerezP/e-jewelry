<?php

namespace App\Exports;

use App\Models\CartItem;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class DeliveryReportExport implements FromCollection, WithHeadings, ShouldAutoSize, WithHeadingRow, WithEvents, ShouldQueue
{
    use Exportable;
    public function headings(): array
    {
        return ['ID', 'Nombre del producto', 'Cantidad', 'Usuario', 'Email', 'Fecha de pago'];
    }

    public function collection()
    {
        $cartItems = CartItem::with(['product', 'user'])
            ->where('state', 'paid')
            ->get();

        return $cartItems->map(function ($item) {
            return [
                'ID' => $item->id,
                'Nombre del producto' => $item->product->name,
                'Cantidad' => $item->amount,
                'Usuario' => $item->user->name,
                'Email' => $item->user->email,
                'Fecha de pago' => $item->updated_at,
            ];
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
