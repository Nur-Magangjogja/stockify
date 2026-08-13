<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromIterator;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;

class ActivityExport implements FromIterator, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $activities;

    public function __construct($activities)
    {
        $this->activities = $activities;
    }

    public function iterator(): \Iterator
    {
        // Convert LazyCollection to an Iterator
        return $this->activities->getIterator();
    }

    public function headings(): array
    {
        return [
            'Waktu',
            'Pengguna',
            'Aksi',
            'Deskripsi',
            'IP Address',
        ];
    }

    public function map($log): array
    {
        return [
            Carbon::parse($log->created_at)->format('Y-m-d H:i'),
            $log->user ? $log->user->name : 'System',
            $log->action,
            $log->description,
            $log->ip_address,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1    => ['font' => ['bold' => true]],
        ];
    }
}
