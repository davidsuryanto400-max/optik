@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<style>
    .stat-card {
        border-radius: 14px;
        border: none;
        overflow: hidden;
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 24px rgba(0,0,0,0.12) !important;
    }
    .icon-circle {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 26px;
        flex-shrink: 0;
    }
    .section-card {
        border-radius: 14px;
        border: none;
        overflow: hidden;
    }
    .section-card .card-header {
        background: #fff;
        border-bottom: 1px solid #f0f0f0;
        padding: 16px 20px;
    }
    .rank-badge {
        width: 26px;
        height: 26px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 11px;
        font-weight: 700;
        background: #fff3cd;
        color: #856404;
        margin-right: 10px;
        flex-shrink: 0;
    }
    .list-scroll {
        max-height: 340px;
        overflow-y: auto;
        scrollbar-width: thin;
    }
    .list-scroll::-webkit-scrollbar { width: 5px; }
    .list-scroll::-webkit-scrollbar-thumb { background: #ddd; border-radius: 10px; }

    .list-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 20px;
        border-bottom: 1px solid #f5f5f5;
        text-decoration: none;
        color: inherit;
        transition: background 0.15s;
    }
    .list-item:hover {
        background: #f8f9fa;
        text-decoration: none;
        color: inherit;
    }
    .list-item:last-child { border-bottom: none; }

    .badge-warning-soft {
        background: #fff9e6;
        color: #856404;
        font-weight: 600;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 12px;
        white-space: nowrap;
    }
    .badge-danger-soft {
        background: #fbeaea;
        color: #dc3545;
        font-weight: 600;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 12px;
        white-space: nowrap;
    }
    .badge-birthday {
        background: #e8f4fd;
        color: #0c63e4;
        font-weight: 600;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 12px;
        white-space: nowrap;
    }
    .badge-today {
        background: #fbeaea;
        color: #dc3545;
        font-weight: 700;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 12px;
    }
    .empty-state {
        text-align: center;
        padding: 30px 20px;
        color: #aaa;
        font-size: 13px;
    }
    .empty-state i { font-size: 28px; margin-bottom: 8px; display: block; }
</style>

{{-- ── ROW 1: Total Pasien (full width stat) ── --}}
<div class="row mb-4">
    <div class="col-12">
        <div class="card stat-card shadow-sm">
            <div class="card-body d-flex align-items-center p-4">
                <div class="icon-circle bg-primary text-white mr-4">
                    <i class="fas fa-users"></i>
                </div>
                <div>
                    <div class="text-muted small font-weight-bold text-uppercase mb-1">Total Pasien Terdaftar</div>
                    <div class="h1 mb-0 font-weight-bolder">{{ number_format($totalPasien, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ── ROW 2: Produk Terlaris ── --}}
<div class="row mb-4">
    <div class="col-12">
        <div class="card section-card shadow-sm">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h6 class="mb-0 font-weight-bold"><i class="fas fa-medal text-warning mr-2"></i> Produk Terlaris</h6>
                <a href="{{ route('produk.index') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="fas fa-external-link-alt mr-1"></i> Lihat Semua
                </a>
            </div>
            <div class="card-body p-0">
                @forelse($produkTerlaris as $index => $produk)
                    <a href="{{ route('produk.index', ['search' => $produk->nama]) }}" class="list-item">
                        <div class="d-flex align-items-center">
                            <span class="rank-badge">{{ $index + 1 }}</span>
                            <span class="font-weight-bold text-dark">{{ $produk->nama }}</span>
                        </div>
                        <span class="text-muted small font-weight-bold">{{ $produk->total_terjual }} terjual</span>
                    </a>
                @empty
                    <div class="empty-state">
                        <i class="fas fa-box-open"></i>
                        Belum ada data penjualan
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

{{-- ── ROW 3: Ulang Tahun | Stok Rendah | Stok Habis ── --}}
<div class="row">

    {{-- Pengingat Ulang Tahun --}}
    <div class="col-md-4 mb-4 mb-md-0">
        <div class="card section-card shadow-sm h-100">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h6 class="mb-0 font-weight-bold">
                    <i class="fas fa-birthday-cake text-danger mr-2"></i> Pengingat Ulang Tahun
                </h6>
                <span class="badge badge-danger-soft">{{ $ultah->count() }}</span>
            </div>
            <div class="card-body p-0">
                <div class="list-scroll">
                    @forelse($ultah as $p)
                        <a href="{{ route('pasien.index', ['search' => $p->nama]) }}" class="list-item">
                            <div>
                                <div class="font-weight-bold text-dark">{{ $p->nama }}</div>
                                <div class="text-muted" style="font-size:12px;">
                                    <i class="fas fa-calendar-alt mr-1"></i>{{ $p->next_bday->format('d F') }}
                                </div>
                            </div>
                            @if($p->days_until == 0)
                                <span class="badge-today">🎂 Hari Ini!</span>
                            @else
                                <span class="badge-birthday">{{ $p->days_until }} Hari</span>
                            @endif
                        </a>
                    @empty
                        <div class="empty-state">
                            <i class="fas fa-cake-candles"></i>
                            Tidak ada ulang tahun dalam 30 hari
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    {{-- Stok Rendah --}}
    <div class="col-md-4 mb-4 mb-md-0">
        <div class="card section-card shadow-sm h-100">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h6 class="mb-0 font-weight-bold">
                    <i class="fas fa-exclamation-triangle text-warning mr-2"></i> Stok Rendah
                </h6>
                <span class="badge-warning-soft">{{ $stokRendah->count() }} produk</span>
            </div>
            <div class="card-body p-0">
                <div class="list-scroll">
                    @forelse($stokRendah as $p)
                        <a href="{{ route('produk.index', ['search' => $p->nama]) }}" class="list-item">
                            <div>
                                <div class="font-weight-bold text-dark">{{ $p->nama }}</div>
                                <div class="text-muted" style="font-size:12px;">{{ $p->merek }}</div>
                            </div>
                            <span class="badge-warning-soft">
                                Sisa {{ $p->stok }}
                                <small class="text-muted">/min {{ $p->stok_minimum }}</small>
                            </span>
                        </a>
                    @empty
                        <div class="empty-state">
                            <i class="fas fa-check-circle text-success"></i>
                            Semua stok aman
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    {{-- Stok Habis --}}
    <div class="col-md-4">
        <div class="card section-card shadow-sm h-100">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h6 class="mb-0 font-weight-bold">
                    <i class="fas fa-box-open text-danger mr-2"></i> Stok Habis
                </h6>
                <span class="badge-danger-soft">{{ $stokHabis->count() }} produk</span>
            </div>
            <div class="card-body p-0">
                <div class="list-scroll">
                    @forelse($stokHabis as $p)
                        <a href="{{ route('produk.index', ['search' => $p->nama]) }}" class="list-item">
                            <div>
                                <div class="font-weight-bold text-dark">{{ $p->nama }}</div>
                                <div class="text-muted" style="font-size:12px;">{{ $p->merek }}</div>
                            </div>
                            <span class="badge-danger-soft">
                                <i class="fas fa-times-circle mr-1"></i>Habis
                            </span>
                        </a>
                    @empty
                        <div class="empty-state">
                            <i class="fas fa-check-circle text-success"></i>
                            Tidak ada stok habis
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

</div>
@endsection