@extends('layouts.app')

@section('title', 'Kartu Stok')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3 no-print">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 font-weight-bold text-primary"><i class="fas fa-history mr-2"></i>Laporan Kartu Stok
                        </h5>
                        <div>
                            <button onclick="window.print()"
                                class="btn btn-primary btn-sm px-4 {{ !$produk ? 'disabled' : '' }}" {{ !$produk ? 'disabled' : '' }}>
                                <i class="fas fa-print mr-1"></i> Cetak Kartu Stok
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('report.kartuStok') }}" method="GET" class="mb-4 no-print">
                        <div class="row">
                            <div class="col-md-5">
                                <label class="small font-weight-bold text-muted text-uppercase">Pilih Produk</label>
                                <select name="produk_id" class="form-control select2" required
                                    onchange="this.form.submit()">
                                    <option value="">-- Cari atau Pilih Produk --</option>
                                    @foreach($all_produks as $p)
                                        <option value="{{ $p->id }}" {{ request('produk_id') == $p->id ? 'selected' : '' }}>
                                            {{ $p->nama }} ({{ $p->merek ?? '-' }}) - {{ optional($p->gudang)->nama }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="small font-weight-bold text-muted text-uppercase">Sampai Tanggal</label>
                                <input type="date" name="end_date"
                                    class="form-control form-control-sm border-light bg-light" value="{{ $endDate }}"
                                    onchange="this.form.submit()">
                            </div>
                            <div class="col-md-1 d-flex align-items-end">
                                <a href="{{ route('report.kartuStok') }}" class="btn btn-outline-secondary btn-sm px-2">
                                    <i class="fas fa-undo"></i>
                                </a>
                            </div>
                        </div>
                    </form>

                    @if($produk)
                        <div class="print-only mb-4 text-center d-none">
                            <h3>KARTU STOK PRODUK</h3>
                            <h4 class="text-primary">{{ $produk->nama }}</h4>
                            <p class="mb-0">Sampai dengan: {{ date('d M Y', strtotime($endDate)) }}</p>
                            <p class="small text-muted">Tanggal Cetak: {{ date('d/m/Y H:i') }}</p>
                            <hr>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <table class="table table-sm table-borderless">
                                    <tr>
                                        <td width="150" class="text-muted small text-uppercase">Nama Produk</td>
                                        <td class="font-weight-bold">: {{ $produk->nama }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted small text-uppercase">Merek</td>
                                        <td class="font-weight-bold">: {{ $produk->merek ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted small text-uppercase">Gudang</td>
                                        <td class="font-weight-bold">: {{ optional($produk->gudang)->nama }}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-sm table-borderless">
                                    <tr>
                                        <td width="150" class="text-muted small text-uppercase">Kategori</td>
                                        <td class="font-weight-bold">: {{ optional($produk->kategori)->nama }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted small text-uppercase">Tipe</td>
                                        <td class="font-weight-bold">: {{ optional($produk->tipe)->nama }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted small text-uppercase">Stok Saat Ini</td>
                                        <td class="font-weight-bold h5 text-primary">: {{ $visibleStock }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-sm table-hover border">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="py-3 text-xs text-uppercase">Tanggal</th>
                                        <th class="py-3 text-xs text-uppercase">Referensi / Catatan</th>
                                        <th class="py-3 text-xs text-uppercase">Tipe Muasi</th>
                                        <th class="py-3 text-xs text-uppercase text-center">Masuk</th>
                                        <th class="py-3 text-xs text-uppercase text-center">Keluar</th>
                                        <th class="py-3 text-xs text-uppercase text-center">Saldo</th>
                                        <th class="py-3 text-xs text-uppercase">Pencatat</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($history as $item)
                                        @php
                                            $tipe = strtolower($item->tipe ?? '');
                                            $isMasuk = (strpos($tipe, 'masuk') !== false || $tipe === 'stok awal');
                                        @endphp
                                        <tr>
                                            <td class="align-middle text-sm">{{ $item->created_at->format('d/m/Y H:i') }}</td>
                                            <td class="align-middle text-sm text-muted">{{ $item->catatan ?? '-' }}</td>
                                            <td class="align-middle text-sm">
                                                @if($isMasuk)
                                                    <span class="text-success small"><i class="fas fa-plus-circle mr-1"></i>
                                                        {{ $item->tipe }}</span>
                                                @else
                                                    <span class="text-danger small"><i class="fas fa-minus-circle mr-1"></i>
                                                        {{ $item->tipe }}</span>
                                                @endif
                                            </td>
                                            <td class="align-middle text-center text-sm font-weight-bold text-success">
                                                {{ $isMasuk ? $item->jumlah : '-' }}
                                            </td>
                                            <td class="align-middle text-center text-sm font-weight-bold text-danger">
                                                {{ !$isMasuk ? $item->jumlah : '-' }}
                                            </td>
                                            <td
                                                class="align-middle text-center text-sm font-weight-bold {{ $item->stok_akhir <= $produk->stok_minimum ? 'text-danger' : 'text-primary' }}">
                                                {{ $item->stok_akhir }}
                                            </td>
                                            <td class="align-middle text-xs text-muted">
                                                {{ $item->created_by ?? (optional($item->user)->name ?? 'Admin') }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-5 text-muted">
                                                Tidak ada riwayat mutasi pada periode ini.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5 text-muted">
                            <i class="fas fa-box-open fa-3x mb-3"></i>
                            <p>Pilih produk terlebih dahulu untuk menampilkan Kartu Stok.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@x.x.x/dist/select2-bootstrap4.min.css">
    <style>
        @media print {
            .no-print {
                display: none !important;
            }

            .print-only {
                display: block !important;
            }

            .main-footer,
            .main-sidebar,
            .content-header,
            .navbar {
                display: none !important;
            }

            .content-wrapper {
                margin-left: 0 !important;
                padding: 0 !important;
            }

            .card {
                border: none !important;
                box-shadow: none !important;
            }

            th,
            td {
                border: 1px solid #dee2e6 !important;
            }
        }

        .select2-container--bootstrap4 .select2-selection--single {
            height: calc(1.5em + 0.75rem + 2px) !important;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function () {
            $('.select2').select2({
                theme: 'bootstrap4',
                width: '100%'
            });
        });
    </script>
@endpush