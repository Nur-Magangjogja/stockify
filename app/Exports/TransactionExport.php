<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;

class TransactionExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $reportData;

    public function __construct(array $reportData)
    {
        $this->reportData = $reportData;
    }

    public function collection()
    {
        return collect($this->reportData['transactions']);
    }

    public function headings(): array
    {
        return [
            'Tanggal',
            'Tipe',
            'SKU',
            'Produk',
            'Kuantitas',
            'User',
            'Catatan'
        ];
    }

    public function map($transaction): array
    {
        return [
            Carbon::parse($transaction->date)->format('Y-m-d H:i'),
            $transaction->type,
            $transaction->product_sku,
            $transaction->product_name,
            $transaction->quantity,
            $transaction->user_name,
            $transaction->notes
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1    => ['font' => ['bold' => true]],
        ];
    }
}
