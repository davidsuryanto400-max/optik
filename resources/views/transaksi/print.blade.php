<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nota Penjualan - {{ $transaksi->no_transaksi }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            margin: 0;
            padding: 20px;
            color: #333;
        }

        .header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }

        .company-info h1 {
            margin: 0;
            font-size: 24px;
            font-weight: bold;
        }

        .invoice-title {
            text-align: right;
        }

        .invoice-title h2 {
            margin: 0;
            font-size: 18px;
            text-transform: uppercase;
        }

        .details {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .details-col {
            width: 48%;
        }

        .details-col p {
            margin: 2px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table th,
        table td {
            text-align: left;
            padding: 8px;
            border-bottom: 1px solid #ddd;
        }

        table th {
            background-color: #f8f9fa;
            font-weight: bold;
        }

        .text-right {
            text-align: right;
        }

        .summary {
            width: 40%;
            margin-left: auto;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 4px 0;
        }

        .summary-row.total {
            font-weight: bold;
            font-size: 16px;
            border-top: 2px solid #333;
            margin-top: 5px;
            padding-top: 5px;
        }

        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 12px;
            color: #777;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }

        @media print {
            body {
                padding: 0;
            }

            .no-print {
                display: none;
            }
        }
    </style>
</head>

<body onload="window.print()">

    <div class="header">
        <div class="company-info">
            <h1>Optik Rapi</h1>
            <p>Jl. Penglihatan Jernih No. 1, Jakarta</p>
            <p>Telp: (021) 123-4567</p>
        </div>
        <div class="invoice-title">
            <h2>NOTA PENJUALAN</h2>
            <p>{{ $transaksi->no_transaksi }}</p>
        </div>
    </div>

    <div class="details">
        <div class="details-col">
            <strong>Kepada Yth:</strong>
            <p class="font-weight-bold">{{ $transaksi->pasien->nama }}</p>
            <p>{{ $transaksi->pasien->alamat ?? '-' }}</p>
            <p>{{ $transaksi->pasien->no_hp ?? '-' }}</p>
        </div>
        <div class="details-col text-right">
            <strong>Tanggal:</strong>
            <p>{{ $transaksi->created_at->translatedFormat('d F Y') }}</p>
            <br>
            <strong>Metode Pembayaran:</strong>
            <p>{{ $transaksi->payment_method }}</p>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%;">No.</th>
                <th>Nama Barang</th>
                <th class="text-right" style="width: 10%;">Qty</th>
                <th class="text-right" style="width: 20%;">Harga Satuan</th>
                <th class="text-right" style="width: 20%;">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transaksi->items as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}.</td>
                    <td>{{ $item->produk->nama }} ({{ $item->produk->tipe->nama ?? 'Item' }})</td>
                    <td class="text-right">{{ $item->qty }}</td>
                    <td class="text-right">Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($item->harga * $item->qty, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="summary">
        <div class="summary-row">
            <span>Subtotal:</span>
            <span>Rp {{ number_format($transaksi->subtotal, 0, ',', '.') }}</span>
        </div>

        @if($transaksi->diskon > 0)
            <div class="summary-row" style="color: green;">
                <span>Diskon:</span>
                <span>- Rp {{ number_format($transaksi->diskon, 0, ',', '.') }}</span>
            </div>
        @endif

        @if($transaksi->pajak > 0)
            <div class="summary-row">
                <span>PPN (11%):</span>
                <span>Rp {{ number_format($transaksi->pajak, 0, ',', '.') }}</span>
            </div>
        @endif

        <div class="summary-row total">
            <span>Grand Total:</span>
            <span>Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</span>
        </div>

        @if($transaksi->bpjs_cover > 0)
            <div class="summary-row" style="color: green; font-weight: bold;">
                <span>Cover BPJS:</span>
                <span>- Rp {{ number_format($transaksi->bpjs_cover, 0, ',', '.') }}</span>
            </div>
            <div class="summary-row" style="border-top: 1px dashed #ccc; padding-top: 5px;">
                <span style="font-weight: bold;">Tagihan Pasien:</span>
                <span style="font-weight: bold;">Rp
                    {{ number_format($transaksi->total_harga - $transaksi->bpjs_cover, 0, ',', '.') }}</span>
            </div>
        @endif

        <div class="summary-row" style="margin-top: 10px;">
            <span>Jumlah Bayar:</span>
            <span>Rp {{ number_format($transaksi->bayar, 0, ',', '.') }}</span>
        </div>

        @if($transaksi->kembalian >= 0)
            <div class="summary-row">
                <span>Kembalian:</span>
                <span>Rp {{ number_format($transaksi->kembalian, 0, ',', '.') }}</span>
            </div>
        @else
            <div class="summary-row" style="color: red;">
                <span>Kurang:</span>
                <span>Rp {{ number_format(abs($transaksi->kembalian), 0, ',', '.') }}</span>
            </div>
        @endif
    </div>

    <div class="footer">
        <p>Terima kasih telah berbelanja di Optik Rapi.</p>
        <p>Barang yang sudah dibeli tidak dapat ditukar atau dikembalikan, kecuali ada perjanjian khusus.</p>
    </div>

</body>

</html>