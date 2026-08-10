<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Laporan Transaksi</title>
<style>
    * { font-family: DejaVu Sans, sans-serif; font-size: 11px; }
    body { margin: 20px; color: #1f2937; }
    h1 { font-size: 18px; font-weight: bold; margin: 0 0 4px 0; }
    .subtitle { color: #6b7280; font-size: 11px; margin-bottom: 16px; }
    table { width: 100%; border-collapse: collapse; }
    thead tr { background: #1e3a5f; color: white; }
    thead th { padding: 8px 10px; text-align: left; font-size: 10px; text-transform: uppercase; }
    tbody tr:nth-child(even) { background: #f9fafb; }
    tbody td { padding: 7px 10px; border-bottom: 1px solid #e5e7eb; }
    .badge-in { background: #d1fae5; color: #065f46; padding: 2px 7px; border-radius: 4px; }
    .badge-out { background: #fee2e2; color: #991b1b; padding: 2px 7px; border-radius: 4px; }
    .footer { margin-top: 20px; color: #9ca3af; font-size: 9px; text-align: right; }
</style>
</head>
<body>
<h1>Laporan Transaksi</h1>
<div class="subtitle">
    Periode: {{ isset($filters['date_from']) ? $filters['date_from'] : 'Semua' }}
    s/d {{ isset($filters['date_to']) ? $filters['date_to'] : now()->format('Y-m-d') }}
    | Dicetak: {{ now()->format('d F Y H:i') }}
</div>

<table style="width:100%; margin-bottom:16px; border-collapse:collapse;">
    <tr>
        <td style="width:22%; background:#d1fae5; padding:8px 12px; border-radius:6px; text-align:center;">
            <div style="font-size:16px; font-weight:bold; color:#065f46;">{{ $report['total_in'] }}</div>
            <div style="color:#10b981; font-size:9px;">Total Masuk</div>
        </td>
        <td style="width:5%;"></td>
        <td style="width:22%; background:#fee2e2; padding:8px 12px; border-radius:6px; text-align:center;">
            <div style="font-size:16px; font-weight:bold; color:#991b1b;">{{ $report['total_out'] }}</div>
            <div style="color:#ef4444; font-size:9px;">Total Keluar</div>
        </td>
        <td style="width:5%;"></td>
        <td style="width:22%; background:#dbeafe; padding:8px 12px; border-radius:6px; text-align:center;">
            <div style="font-size:16px; font-weight:bold; color:#1e40af;">{{ number_format($report['total_in_qty']) }}</div>
            <div style="color:#3b82f6; font-size:9px;">Qty Masuk</div>
        </td>
        <td style="width:5%;"></td>
        <td style="width:22%; background:#fef3c7; padding:8px 12px; border-radius:6px; text-align:center;">
            <div style="font-size:16px; font-weight:bold; color:#92400e;">{{ number_format($report['total_out_qty']) }}</div>
            <div style="color:#d97706; font-size:9px;">Qty Keluar</div>
        </td>
    </tr>
</table>

<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Referensi</th>
            <th>Produk</th>
            <th>Tipe</th>
            <th style="text-align:center;">Qty</th>
            <th>Petugas</th>
            <th>Tanggal</th>
        </tr>
    </thead>
    <tbody>
        @foreach($report['transactions'] as $i => $tx)
        <tr>
            <td>{{ $i + 1 }}</td>
            <td style="font-family:monospace; font-size:9px;">#{{ $tx->id }}</td>
            <td>{{ $tx->product_name }}</td>
            <td>
                @if($tx->type === 'Masuk')
                    <span class="badge-in">Masuk</span>
                @else
                    <span class="badge-out">Keluar</span>
                @endif
            </td>
            <td style="text-align:center; font-weight:bold;">
                {{ $tx->type === 'Masuk' ? '+' : '-' }}{{ number_format($tx->quantity) }}
            </td>
            <td>{{ $tx->user_name }}</td>
            <td>{{ \Carbon\Carbon::parse($tx->date)->format('d/m/Y') }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<div class="footer">Stockify — Sistem Manajemen Stok | {{ now()->format('d/m/Y H:i') }}</div>
</body>
</html>
