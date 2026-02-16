@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <style>
        .dashboard-card {
            border-radius: 12px;
            border: none;
            overflow: hidden;
        }

        .icon-circle {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .rank-circle {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            font-weight: bold;
            background: #fff3cd;
            color: #856404;
            margin-right: 8px;
        }

        .custom-scroll {
            max-height: 380px;
            overflow-y: auto;
            scrollbar-width: thin;
        }

        .custom-scroll::-webkit-scrollbar {
            width: 6px;
        }

        .custom-scroll::-webkit-scrollbar-thumb {
            background: #e0e0e0;
            border-radius: 10px;
        }

        .list-item-hover:hover {
            background: #f8f9fa;
        }

        .badge-soft-danger {
            background: #fbeaea;
            color: #dc3545;
            font-weight: 600;
        }

        .badge-soft-warning {
            background: #fff9e6;
            color: #856404;
            font-weight: 600;
        }
    </style>

    <!-- Row 1: Main Stats -->
    <div class="row mb-4">
        <div class="col-md-6 mb-3 mb-md-0">
            <div class="card dashboard-card shadow-sm h-100">
                <div class="card-body d-flex align-items-center p-4">
                    <div class="icon-circle bg-primary text-white mr-4" style="width: 70px; height: 70px; font-size: 28px;">
                        <i class="fas fa-users"></i>
                    </div>
                    <div>
                        <div class="text-muted small font-weight-bold text-uppercase mb-1">Total Pasien</div>
                        <div class="h2 mb-0 font-weight-bolder">{{ number_format($totalPasien, 0, ',', '.') }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card dashboard-card shadow-sm h-100">
                <div class="card-body d-flex align-items-center p-4">
                    <div class="icon-circle bg-primary text-white mr-4" style="width: 70px; height: 70px; font-size: 28px;">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <div>
                        <div class="text-muted small font-weight-bold text-uppercase mb-1">Total Penjualan</div>
                        <div class="h2 mb-0 font-weight-bolder">Rp {{ number_format($totalPenjualan, 0, ',', '.') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Row 2: Age & Best Sellers -->
    <div class="row mb-4">
        <div class="col-lg-7 mb-4 mb-lg-0">
            <div class="card dashboard-card shadow-sm h-100">
                <div class="card-body d-flex align-items-center justify-content-center py-5">
                    <div class="text-center">
                        <div class="icon-circle bg-success text-white mx-auto mb-4"
                            style="width: 100px; height: 100px; font-size: 40px;">
                            <i class="fas fa-user-clock"></i>
                        </div>
                        <div class="text-muted font-weight-bold text-uppercase mb-1">Rata-rata Umur Pasien</div>
                        <div class="display-4 font-weight-bolder">{{ $avgUmur }} <small class="h3 mb-0">Tahun</small></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="card dashboard-card shadow-sm h-100">
                <div class="card-header bg-white border-0 pt-4 pb-0">
                    <h5 class="card-title font-weight-bold"><i class="fas fa-medal text-warning mr-2"></i> Produk Terlaris
                    </h5>
                </div>
                <div class="card-body">
                    @foreach($produkTerlaris as $index => $produk)
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="d-flex align-items-center">
                                <span class="rank-circle">{{ $index + 1 }}</span>
                                <span class="font-weight-bold text-dark">{{ $produk->nama }}</span>
                            </div>
                            <span class="text-muted small font-weight-bold">{{ $produk->total_terjual }} terjual</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Row 3: Stylized Lists -->
    <div class="row">
        <!-- Birthdays -->
        <div class="col-md-4 mb-4 mb-md-0">
            <div class="card dashboard-card shadow-sm">
                <div class="card-header bg-white border-0 pt-4 pb-2">
                    <h6 class="font-weight-bold text-primary mb-0"><i class="fas fa-birthday-cake text-danger mr-2"></i>
                        Pengingat Ulang Tahun</h6>
                </div>
                <div class="card-body p-0">
                    <div class="custom-scroll px-3 pb-3">
                        @forelse($ultah as $p)
                            <div class="d-flex justify-content-between align-items-center py-3 border-bottom list-item-hover">
                                <div>
                                    <div class="font-weight-bold text-dark">{{ $p->nama }}</div>
                                    <div class="text-muted small">{{ $p->next_bday->format('d F') }}</div>
                                </div>
                                <span class="badge badge-soft-danger px-3 py-2 rounded-pill">
                                    @if($p->days_until == 0) Hari Ini @else {{ $p->days_until }} Hari @endif
                                </span>
                            </div>
                        @empty
                            <div class="text-center py-4 text-muted small">Tidak ada yang ulang tahun dekat ini</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Low Stock -->
        <div class="col-md-4 mb-4 mb-md-0">
            <div class="card dashboard-card shadow-sm">
                <div class="card-header bg-white border-0 pt-4 pb-2">
                    <h6 class="font-weight-bold text-primary mb-0"><i
                            class="fas fa-exclamation-triangle text-warning mr-2"></i> Perhatian Stok Rendah</h6>
                </div>
                <div class="card-body p-0">
                    <div class="custom-scroll px-3 pb-3">
                        @forelse($stokRendah as $p)
                            <div class="d-flex justify-content-between align-items-center py-3 border-bottom list-item-hover">
                                <div>
                                    <div class="font-weight-bold text-dark">{{ $p->nama }}</div>
                                    <div class="text-muted small">{{ $p->merek }}</div>
                                </div>
                                <span class="badge badge-soft-warning px-3 py-2 rounded-pill">
                                    Sisa: {{ $p->stok }} <small class="opacity-50">(Min: {{ $p->stok_minimum }})</small>
                                </span>
                            </div>
                        @empty
                            <div class="text-center py-4 text-muted small">Semua stok aman</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- No Stock -->
        <div class="col-md-4">
            <div class="card dashboard-card shadow-sm">
                <div class="card-header bg-white border-0 pt-4 pb-2">
                    <h6 class="font-weight-bold text-primary mb-0"><i class="fas fa-box-open text-danger mr-2"></i>
                        Perhatian Stok Habis</h6>
                </div>
                <div class="card-body p-0">
                    <div class="custom-scroll px-3 pb-3">
                        @forelse($stokHabis as $p)
                            <div class="d-flex justify-content-between align-items-center py-3 border-bottom list-item-hover">
                                <div>
                                    <div class="font-weight-bold text-dark">{{ $p->nama }}</div>
                                    <div class="text-muted small">{{ $p->merek }}</div>
                                </div>
                                <span class="badge badge-danger px-3 py-2 rounded-pill">Habis</span>
                            </div>
                        @empty
                            <div class="text-center py-4 text-muted small">Tidak ada stok habis</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection