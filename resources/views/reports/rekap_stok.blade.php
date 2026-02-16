@extends('layouts.app')

@section('title', 'Rekap Stok')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3 no-print">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 font-weight-bold text-primary"><i class="fas fa-file-alt mr-2"></i>Laporan Rekap
                            Stok</h5>
                        <div>
                            <button onclick="window.print()" class="btn btn-primary btn-sm px-4">
                                <i class="fas fa-print mr-1"></i> Cetak Laporan
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('report.rekapStok') }}" method="GET" class="mb-4 no-print">
                        <div class="row">
                            <div class="col-md-3">
                                <label class="small font-weight-bold text-muted">Tipe Produk</label>
                                <select name="tipe_id" class="form-control form-control-sm border-light bg-light"
                                    onchange="this.form.submit()">
                                    <option value="">Semua Tipe</option>
                                    @foreach($all_tipes as $t)
                                        <option value="{{ $t->id }}" {{ request('tipe_id') == $t->id ? 'selected' : '' }}>
                                            {{ $t->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="small font-weight-bold text-muted">Kategori</label>
                                <select name="kategori_id" class="form-control form-control-sm border-light bg-light"
                                    onchange="this.form.submit()">
                                    <option value="">Semua Kategori</option>
                                    @foreach($all_kategoris as $k)
                                        <option value="{{ $k->id }}" {{ request('kategori_id') == $k->id ? 'selected' : '' }}>
                                            {{ $k->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="small font-weight-bold text-muted">Gudang</label>
                                <select name="gudang_id" class="form-control form-control-sm border-light bg-light"
                                    onchange="this.form.submit()">
                                    <option value="">Semua Gudang</option>
                                    @foreach($all_gudangs as $g)
                                        <option value="{{ $g->id }}" {{ request('gudang_id') == $g->id ? 'selected' : '' }}>
                                            {{ $g->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 d-flex align-items-end">
                                <a href="{{ route('report.rekapStok') }}" class="btn btn-outline-secondary btn-sm px-3">
                                    <i class="fas fa-undo mr-1"></i> Reset
                                </a>
                            </div>
                        </div>
                    </form>

                    <div class="print-only mb-4 text-center d-none">
                        <h3>REKAP STOK OPTIK RAPI</h3>
                        <p class="mb-0">Tanggal Cetak: {{ date('d/m/Y H:i') }}</p>
                        <hr>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover border">
                            <thead class="bg-light">
                                <tr>
                                    <th class="text-xs text-uppercase py-3">#</th>
                                    <th class="text-xs text-uppercase py-3">Produk</th>
                                    <th class="text-xs text-uppercase py-3">Merek</th>
                                    <th class="text-xs text-uppercase py-3">Kategori</th>
                                    <th class="text-xs text-uppercase py-3">Tipe</th>
                                    <th class="text-xs text-uppercase py-3">Gudang</th>
                                    <th class="text-xs text-uppercase py-3 text-right">Harga Jual</th>
                                    <th class="text-xs text-uppercase py-3 text-center">Stok</th>
                                    <th class="text-xs text-uppercase py-3 text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $totalStok = 0; @endphp
                                @forelse($produks as $index => $produk)
                                    @php $totalStok += $produk->stok; @endphp
                                    <tr>
                                        <td class="align-middle text-sm">{{ $index + 1 }}</td>
                                        <td class="align-middle">
                                            <div class="font-weight-bold text-dark">{{ $produk->nama }}</div>
                                            <small class="text-muted">{{ $produk->kode }}</small>
                                        </td>
                                        <td class="align-middle text-sm">{{ $produk->merek ?? '-' }}</td>
                                        <td class="align-middle text-sm">{{ optional($produk->kategori)->nama }}</td>
                                        <td class="align-middle text-sm">{{ optional($produk->tipe)->nama }}</td>
                                        <td class="align-middle text-sm">{{ optional($produk->gudang)->nama }}</td>
                                        <td class="align-middle text-sm text-right font-weight-bold">
                                            Rp {{ number_format($produk->harga_jual, 0, ',', '.') }}
                                        </td>
                                        <td class="align-middle text-center font-weight-bold text-primary">
                                            {{ $produk->stok }}
                                        </td>
                                        <td class="align-middle text-center">
                                            @if($produk->stok < $produk->stok_minimum)
                                                <span class="badge badge-danger text-xs px-2 py-1">HABIS/RENDAH</span>
                                            @elseif($produk->stok == $produk->stok_minimum)
                                                <span class="badge badge-warning text-xs px-2 py-1 text-white">MENIPIS</span>
                                            @else
                                                <span class="badge badge-success text-xs px-2 py-1">AMAN</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center py-5 text-muted">
                                            <i class="fas fa-search fa-3x mb-3"></i>
                                            <p class="mb-0">Tidak ada data produk yang sesuai dengan filter.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                            @if($produks->isNotEmpty())
                                <tfoot class="bg-light">
                                    <tr>
                                        <th colspan="7" class="text-right py-3 text-uppercase text-xs">Total Inventori
                                            Keseluruhan:</th>
                                        <th class="text-center py-3 h6 font-weight-bold text-primary">{{ $totalStok }}</th>
                                        <th>Unit</th>
                                    </tr>
                                </tfoot>
                            @endif
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
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

            .table-responsive {
                overflow: visible !important;
            }

            table {
                width: 100% !important;
                border-collapse: collapse !important;
            }

            th,
            td {
                border: 1px solid #dee2e6 !important;
                padding: 8px !important;
            }

            .badge {
                border: 1px solid #000 !important;
                color: #000 !important;
                background: transparent !important;
            }
        }
    </style>
@endpush