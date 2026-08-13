<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromIterator;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class StockExport implements FromIterator, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $reportData;

    public function __construct(array $reportData)
    {
        $this->reportData = $reportData;
    }

    public function iterator(): \Iterator
    {
        return $this->reportData['products']->getIterator();
    }

    public function headings(): array
    {
        return [
            'SKU',
            'Nama Produk',
            'Kategori',
            'Harga Beli',
            'Min. Stok',
            'Stok Saat Ini',
            'Status',
            'Nilai Stok'
        ];
    }

    public function map($product): array
    {
        return [
            $product->code,
            $product->name,
            $product->category_name,
            $product->buy_price,
            $product->min_stock,
            $product->stock . ' ' . $product->unit,
            $product->stock_status_label,
            $product->stock_value
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1    => ['font' => ['bold' => true]],
        ];
    }
}
