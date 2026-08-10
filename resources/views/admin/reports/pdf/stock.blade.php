<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Laporan Stok Barang</title>
<style>
    * { font-family: DejaVu Sans, sans-serif; font-size: 11px; }
    body { margin: 20px; color: #1f2937; }
    h1 { font-size: 18px; font-weight: bold; margin: 0 0 4px 0; }
    .subtitle { color: #6b7280; font-size: 11px; margin-bottom: 20px; }
    .summary { display: flex; gap: 12px; margin-bottom: 16px; }
    .card { background: #f3f4f6; padding: 8px 14px; border-radius: 6px; flex: 1; text-align: center; }
    .card .num { font-size: 16px; font-weight: bold; }
    table { width: 100%; border-collapse: collapse; margin-top: 8px; }
    thead tr { background: #1e3a5f; color: white; }
    thead th { padding: 8px 10px; text-align: left; font-size: 10px; text-transform: uppercase; }
    tbody tr:nth-child(even) { background: #f9fafb; }
    tbody td { padding: 7px 10px; border-bottom: 1px solid #e5e7eb; }
    .badge-ok { background: #d1fae5; color: #065f46; padding: 2px 7px; border-radius: 4px; }
    .badge-low { background: #fef3c7; color: #92400e; padding: 2px 7px; border-radius: 4px; }
    .badge-out { background: #fee2e2; color: #991b1b; padding: 2px 7px; border-radius: 4px; }
    .footer { margin-top: 20px; color: #9ca3af; font-size: 9px; text-align: right; }
</style>
</head>
<body>
<h1>Laporan Stok Barang</h1>
<div class="subtitle">Dicetak: {{ now()->format('d F Y H:i') }}</div>

<table style="width:100%; margin-bottom:16px; border-collapse:collapse;">
    <tr>
        <td style="width:25%; background:#dbeafe; padding:8px 12px; border-radius:6px; text-align:center;">
            <div style="font-size:16px; font-weight:bold; color:#1e40af;">{{ $report['total_products'] }}</div>
            <div style="color:#3b82f6; font-size:9px;">Total Produk</div>
        </td>
        <td style="width:5%;"></td>
        <td style="width:25%; background:#d1fae5; padding:8px 12px; border-radius:6px; text-align:center;">
            <div style="font-size:13px; font-weight:bold; color:#065f46;">Rp {{ number_format($report['total_stock_value'], 0, ',', '.') }}</div>
            <div style="color:#10b981; font-size:9px;">Nilai Stok</div>
        </td>
        <td style="width:5%;"></td>
        <td style="width:25%; background:#fef3c7; padding:8px 12px; border-radius:6px; text-align:center;">
            <div style="font-size:16px; font-weight:bold; color:#92400e;">{{ $report['low_stock_count'] }}</div>
            <div style="color:#d97706; font-size:9px;">Stok Menipis</div>
        </td>
        <td style="width:5%;"></td>
        <td style="width:25%; background:#fee2e2; padding:8px 12px; border-radius:6px; text-align:center;">
            <div style="font-size:16px; font-weight:bold; color:#991b1b;">{{ $report['out_of_stock'] }}</div>
            <div style="color:#ef4444; font-size:9px;">Stok Habis</div>
        </td>
    </tr>
</table>

<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Kode</th>
            <th>Nama Produk</th>
            <th>Kategori</th>
            <th style="text-align:center;">Stok</th>
            <th style="text-align:center;">Min</th>
            <th style="text-align:right;">Nilai</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach($report['products'] as $i => $product)
        <tr>
            <td>{{ $i + 1 }}</td>
            <td style="font-family: monospace;">{{ $product->code }}</td>
            <td>{{ $product->name }}</td>
            <td>{{ $product->category_name ?? '-' }}</td>
            <td style="text-align:center; font-weight:bold;">{{ $product->stock }} {{ $product->unit }}</td>
            <td style="text-align:center; color:#9ca3af;">{{ $product->min_stock }}</td>
            <td style="text-align:right;">Rp {{ number_format($product->stock * $product->buy_price, 0, ',', '.') }}</td>
            <td>
                @if($product->stock <= 0)
                    <span class="badge-out">Habis</span>
                @elseif($product->is_low_stock)
                    <span class="badge-low">Menipis</span>
                @else
                    <span class="badge-ok">Tersedia</span>
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<div class="footer">Stockify — Sistem Manajemen Stok | {{ now()->format('d/m/Y H:i') }}</div>
</body>
</html>
