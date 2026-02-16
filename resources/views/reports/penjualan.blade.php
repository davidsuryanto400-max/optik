@extends('layouts.app')

@section('title', 'Laporan Penjualan')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3 no-print">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 font-weight-bold text-primary"><i
                                class="fas fa-file-invoice-dollar mr-2"></i>Laporan Penjualan</h5>
                        <div>
                            <button onclick="window.print()" class="btn btn-primary btn-sm px-4">
                                <i class="fas fa-print mr-1"></i> Cetak Laporan
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Filters -->
                    <form action="{{ route('report.penjualan') }}" method="GET" class="mb-4 no-print">
                        <div class="row">
                            <div class="col-md-3">
                                <label class="small font-weight-bold text-muted text-uppercase">Dari Tanggal</label>
                                <input type="date" name="start_date"
                                    class="form-control form-control-sm border-light bg-light" value="{{ $startDate }}"
                                    onchange="this.form.submit()">
                            </div>
                            <div class="col-md-3">
                                <label class="small font-weight-bold text-muted text-uppercase">Sampai Tanggal</label>
                                <input type="date" name="end_date"
                                    class="form-control form-control-sm border-light bg-light" value="{{ $endDate }}"
                                    onchange="this.form.submit()">
                            </div>
                            <div class="col-md-1 d-flex align-items-end">
                                <a href="{{ route('report.penjualan') }}" class="btn btn-outline-secondary btn-sm px-2">
                                    <i class="fas fa-undo"></i>
                                </a>
                            </div>
                        </div>
                    </form>

                    <!-- Summary Cards -->
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="card bg-primary text-white border-0 shadow-sm">
                                <div class="card-body p-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <div class="text-xs text-uppercase opacity-75">Total Omzet</div>
                                            <div class="h4 mb-0 font-weight-bolder">Rp
                                                {{ number_format($summary['total_sales'], 0, ',', '.') }}</div>
                                        </div>
                                        <i class="fas fa-money-bill-wave fa-2x opacity-25"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-info text-white border-0 shadow-sm">
                                <div class="card-body p-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <div class="text-xs text-uppercase opacity-75">Jumlah Transaksi</div>
                                            <div class="h4 mb-0 font-weight-bolder">{{ $summary['total_count'] }} Transaksi
                                            </div>
                                        </div>
                                        <i class="fas fa-shopping-cart fa-2x opacity-25"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-white border shadow-sm">
                                <div class="card-body p-3">
                                    <div class="text-xs text-uppercase text-muted mb-2">Metode Pembayaran</div>
                                    <div class="d-flex flex-wrap">
                                        @foreach($summary['payment_methods'] as $method => $count)
                                            <span class="badge badge-light border mr-2 mb-1">{{ $method }}: {{ $count }}</span>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="print-only mb-4 text-center d-none">
                        <h3>LAPORAN PENJUALAN</h3>
                        <p class="mb-0">Periode: {{ date('d M Y', strtotime($startDate)) }} -
                            {{ date('d M Y', strtotime($endDate)) }}</p>
                        <p class="small text-muted">Tanggal Cetak: {{ date('d/m/Y H:i') }}</p>
                        <hr>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-sm table-hover border">
                            <thead class="bg-light">
                                <tr>
                                    <th class="py-3 text-xs text-uppercase pl-3">No. Transaksi</th>
                                    <th class="py-3 text-xs text-uppercase text-center">Tanggal</th>
                                    <th class="py-3 text-xs text-uppercase">Pasien</th>
                                    <th class="py-3 text-xs text-uppercase">Produk</th>
                                    <th class="py-3 text-xs text-uppercase text-center">Metode</th>
                                    <th class="py-3 text-xs text-uppercase text-right pr-3">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($transaksis as $trx)
                                    <tr>
                                        <td class="align-middle text-sm font-weight-bold pl-3">{{ $trx->no_transaksi }}</td>
                                        <td class="align-middle text-sm text-center">{{ $trx->created_at->format('d/m/Y') }}
                                        </td>
                                        <td class="align-middle text-sm">{{ $trx->pasien->nama }}</td>
                                        <td class="align-middle text-xs text-muted">
                                            @foreach($trx->items as $item)
                                                <div>- {{ $item->produk->nama }} ({{ $item->qty }})</div>
                                            @endforeach
                                        </td>
                                        <td class="align-middle text-sm text-center">
                                            <span class="badge badge-light border">{{ $trx->payment_method }}</span>
                                        </td>
                                        <td class="align-middle text-sm font-weight-bold text-right pr-3">
                                            Rp {{ number_format($trx->total_harga, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-5 text-muted">
                                            Tidak ada data penjualan pada periode ini.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                            @if($transaksis->isNotEmpty())
                                <tfoot class="bg-light font-weight-bold">
                                    <tr>
                                        <td colspan="5" class="text-right py-3 pl-3">TOTAL KESELURUHAN</td>
                                        <td class="text-right py-3 pr-3 text-primary h5 font-weight-bold">
                                            Rp {{ number_format($summary['total_sales'], 0, ',', '.') }}
                                        </td>
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

@section('styles')
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

            .table thead th {
                background-color: #f8f9fa !important;
                -webkit-print-color-adjust: exact;
            }
        }
    </style>
@endsection