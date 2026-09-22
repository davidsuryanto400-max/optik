<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekam Medis - {{ $pasien->nama }}</title>
    <style>
        /* Hilangkan header & footer bawaan browser saat print */
        @page {
            size: A4;
            margin: 0; /* Margin 0 di @page penting untuk menyembunyikan header/footer (localhost, halaman, dll) bawaan browser */
        }

        body {
            font-family: Arial, sans-serif;
            color: #000;
            line-height: 1.3;
            margin: 0;
            padding: 1.5cm; /* Padding dipindah ke body agar konten tidak mepet tepi kertas */
        }

        .card-container {
            width: 100%;
            border: 2px solid #000;
            padding: 18px 20px;
            box-sizing: border-box;
            margin-bottom: 25px;
            page-break-inside: avoid;
        }

        /* Header: hanya No di kanan atas */
        .card-header {
            text-align: right;
            font-size: 14px;
            margin-bottom: 14px;
        }

        /* Info rows: Nama, Umur, Frame, Lensa, Alamat, PD */
        .info-section {
            font-size: 14px;
            margin-bottom: 16px;
        }

        .info-row {
            display: flex;
            align-items: baseline;
            margin-bottom: 7px;
        }

        .info-label {
            width: 65px;
            flex-shrink: 0;
        }

        .info-sep {
            width: 12px;
            flex-shrink: 0;
        }

        .info-value {
            flex-grow: 1;
            border-bottom: 1px dotted #333;
            min-height: 17px;
            padding-left: 4px;
        }

        /* PD baris khusus */
        .pd-row {
            display: flex;
            align-items: baseline;
            margin-bottom: 7px;
        }

        .pd-value-box {
            width: 90px;
            border-bottom: 1px dotted #333;
            text-align: center;
            min-height: 17px;
            padding-left: 4px;
        }

        .pd-unit {
            margin-left: 6px;
            font-size: 14px;
        }

        /* Tabel resep di bawah PD */
        .table-section {
            margin-bottom: 14px;
        }

        table.rx-table {
            width: 100%;
            border-collapse: collapse;
            text-align: center;
            font-size: 14px;
        }

        table.rx-table th,
        table.rx-table td {
            border: 1px solid #000;
            padding: 9px 6px;
        }

        table.rx-table th {
            font-weight: normal;
            background: #fff;
        }

        table.rx-table td:first-child {
            width: 35px;
            font-weight: bold;
        }

        /* Tanggal di bawah kartu */
        .card-footer-date {
            text-align: right;
            font-size: 13px;
            margin-top: 10px;
        }
    </style>
</head>

<body onload="window.print()">
@php
    $riwayats = $pasien->riwayatPemeriksaans;
@endphp

@if($riwayats->count() > 0)
    @foreach($riwayats as $riwayat)
    @php
        // Hitung umur saat pemeriksaan tersebut dilakukan secara bulat (integer)
        $umurCetak = '-';
        if ($pasien->tgl_lahir) {
            $umurCetak = floor(\Carbon\Carbon::parse($pasien->tgl_lahir)->diffInYears($riwayat->created_at)) . ' Tahun';
        }

        // Ambil nama frame dan lensa dari transaksi yang terhubung, jika ada
        $frameName = $riwayat->frame;
        $lensaName = $riwayat->lensa;
        
        if ($riwayat->transaksi) {
            $frames = [];
            $lensas = [];
            foreach($riwayat->transaksi->items as $item) {
                if ($item->produk && $item->produk->tipe) {
                    $tipe = strtolower($item->produk->tipe->nama);
                    if (strpos($tipe, 'frame') !== false) {
                        $frames[] = $item->produk->nama;
                    } elseif (strpos($tipe, 'lensa') !== false) {
                        $lensas[] = $item->produk->nama;
                    }
                }
            }
            if (count($frames) > 0) $frameName = implode(', ', $frames);
            if (count($lensas) > 0) $lensaName = implode(', ', $lensas);
        }
    @endphp
    <div class="card-container">

        {{-- Header: No resep kanan atas --}}
        <div class="card-header">
            No. {{ $riwayat->no_resep }}
        </div>

        {{-- Informasi pasien --}}
        <div class="info-section">
            <div class="info-row">
                <div class="info-label">Nama</div>
                <div class="info-sep">:</div>
                <div class="info-value">{{ $pasien->nama }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Umur</div>
                <div class="info-sep">:</div>
                <div class="info-value">{{ $umurCetak }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Frame</div>
                <div class="info-sep">:</div>
                <div class="info-value">{{ $frameName }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Lensa</div>
                <div class="info-sep">:</div>
                <div class="info-value">{{ $lensaName }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Alamat</div>
                <div class="info-sep">:</div>
                <div class="info-value">{{ $pasien->alamat }}</div>
            </div>
            <div class="pd-row">
                <div class="info-label">PD</div>
                <div class="info-sep">:</div>
                <div class="pd-value-box">{{ $riwayat->pd }}</div>
                <span class="pd-unit">m/m</span>
            </div>
        </div>

        {{-- Tabel SPH / CYL / AX / ADD di bawah PD --}}
        <div class="table-section">
            <table class="rx-table">
                <thead>
                    <tr>
                        <th></th>
                        <th>SPH</th>
                        <th>CYL</th>
                        <th>AX</th>
                        <th>ADD</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>R</td>
                        <td>{{ $riwayat->sph_r }}</td>
                        <td>{{ $riwayat->cyl_r }}</td>
                        <td>{{ $riwayat->ax_r }}</td>
                        <td>{{ $riwayat->add_r }}</td>
                    </tr>
                    <tr>
                        <td>L</td>
                        <td>{{ $riwayat->sph_l }}</td>
                        <td>{{ $riwayat->cyl_l }}</td>
                        <td>{{ $riwayat->ax_l }}</td>
                        <td>{{ $riwayat->add_l }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        {{-- Tanggal di bawah kartu --}}
        <div class="card-footer-date">
            Nganjuk, {{ $riwayat->created_at->format('d M Y') }}
        </div>

    </div>
    @endforeach

@else
    {{-- Fallback: pakai data langsung dari tabel pasien --}}
    <div class="card-container">

        <div class="card-header">
            No. {{ $pasien->no_resep }}
        </div>

        <div class="info-section">
            <div class="info-row">
                <div class="info-label">Nama</div>
                <div class="info-sep">:</div>
                <div class="info-value">{{ $pasien->nama }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Umur</div>
                <div class="info-sep">:</div>
                <div class="info-value">{{ $pasien->usia ?? '-' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Frame</div>
                <div class="info-sep">:</div>
                <div class="info-value">{{ $pasien->frame }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Lensa</div>
                <div class="info-sep">:</div>
                <div class="info-value">{{ $pasien->lensa }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Alamat</div>
                <div class="info-sep">:</div>
                <div class="info-value">{{ $pasien->alamat }}</div>
            </div>
            <div class="pd-row">
                <div class="info-label">PD</div>
                <div class="info-sep">:</div>
                <div class="pd-value-box">{{ $pasien->pd }}</div>
                <span class="pd-unit">m/m</span>
            </div>
        </div>

        <div class="table-section">
            <table class="rx-table">
                <thead>
                    <tr>
                        <th></th>
                        <th>SPH</th>
                        <th>CYL</th>
                        <th>AX</th>
                        <th>ADD</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>R</td>
                        <td>{{ $pasien->sph_r }}</td>
                        <td>{{ $pasien->cyl_r }}</td>
                        <td>{{ $pasien->ax_r }}</td>
                        <td>{{ $pasien->add_r }}</td>
                    </tr>
                    <tr>
                        <td>L</td>
                        <td>{{ $pasien->sph_l }}</td>
                        <td>{{ $pasien->cyl_l }}</td>
                        <td>{{ $pasien->ax_l }}</td>
                        <td>{{ $pasien->add_l }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="card-footer-date">
            Nganjuk, {{ date('d M Y') }}
        </div>

    </div>
@endif

</body>
</html>