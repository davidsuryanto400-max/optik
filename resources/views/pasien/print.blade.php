<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekam Medis - {{ $pasien->nama }}</title>
    <style>
        @page {
            size: A4;
            margin: 2cm;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #333;
            line-height: 1.5;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
        }

        .header {
            margin-bottom: 2rem;
            border-bottom: 2px solid #333;
            padding-bottom: 1rem;
        }

        .title {
            font-size: 24px;
            font-weight: bold;
            margin: 0;
        }

        .subtitle {
            font-size: 14px;
            margin: 0;
            color: #555;
            display: flex;
            justify-content: space-between;
            margin-top: 5px;
        }

        .section-title {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 10px;
            margin-top: 2rem;
        }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 2rem;
            font-size: 14px;
        }

        .info-item {
            margin-bottom: 8px;
        }

        .label {
            color: #555;
            display: inline-block;
            width: 80px;
        }

        .exam-card {
            border: 1px solid #ddd;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            overflow: hidden;
        }

        .exam-header {
            padding: 15px 20px;
            font-weight: bold;
            font-size: 16px;
            border-bottom: 1px solid #eee;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
        }

        th {
            background-color: #f8f9fa;
            padding: 10px 20px;
            text-align: left;
            font-weight: 600;
            color: #555;
        }

        td {
            padding: 12px 20px;
            border-bottom: 1px solid #eee;
        }

        tr:last-child td {
            border-bottom: none;
        }

        .pd-cell {
            text-align: center;
            vertical-align: middle;
            font-weight: bold;
        }

        .scan-line {
            height: 1px;
            background: #eee;
            margin: 0 20px;
        }

        @media print {
            body {
                margin: 0;
            }

            .container {
                width: 100%;
                max-width: none;
            }

            .exam-card {
                break-inside: avoid;
            }
        }
    </style>
</head>

<body onload="window.print()">
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1 class="title">Riwayat Pemeriksaan Pasien</h1>
            <div style="font-size: 18px; font-weight: 600; margin-top: 5px;">{{ $pasien->nama }}</div>
            <div class="subtitle">
                <span>ID Pasien: {{ str_pad($pasien->id, 4, '0', STR_PAD_LEFT) }}</span>
                <span>Dicetak pada: {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</span>
            </div>
        </div>

        <!-- Informasi Pasien -->
        <div class="section-title">Informasi Pasien</div>
        <div class="info-grid">
            <div>
                <div class="info-item">Telepon: {{ $pasien->no_hp ?? '-' }}</div>
                <div class="info-item">Alamat: {{ $pasien->alamat ?? '-' }}</div>
            </div>
            <div>
                <div class="info-item">Tanggal Lahir:
                    {{ $pasien->tgl_lahir ? $pasien->tgl_lahir->translatedFormat('d F Y') : '-' }} ({{ $pasien->usia }})
                </div>
            </div>
        </div>

        <!-- Detail Riwayat -->
        <div class="section-title">Detail Riwayat Pemeriksaan</div>

        @forelse($pasien->riwayatPemeriksaans as $riwayat)
            <div class="exam-card">
                <div class="exam-header">
                    Tanggal: {{ $riwayat->created_at->translatedFormat('d F Y') }}
                </div>
                <table>
                    <thead>
                        <tr>
                            <th width="30%">Mata</th>
                            <th width="25%">SPH</th>
                            <th width="25%">CYL</th>
                            <th width="20%" style="text-align: center;">PD (mm)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Kanan (OD)</td>
                            <td>{{ $riwayat->sph_r ?? '-' }}</td>
                            <td>{{ $riwayat->cyl_r ?? '-' }}</td>
                            <td rowspan="2" class="pd-cell">{{ $riwayat->pd ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td>Kiri (OS)</td>
                            <td>{{ $riwayat->sph_l ?? '-' }}</td>
                            <td>{{ $riwayat->cyl_l ?? '-' }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        @empty
            <p style="color: #777; font-style: italic;">Belum ada data pemeriksaan.</p>
        @endforelse

        <!-- Placeholder for potential history list if we had more data -->
        <!-- 
        <div class="exam-card">
            <div class="exam-header">Tanggal: 10 September 2022</div>
            <table>...</table>
        </div> 
        -->

    </div>
</body>

</html>