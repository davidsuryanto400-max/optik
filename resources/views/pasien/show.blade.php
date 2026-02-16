<div class="modal-header border-0 pb-0">
    <h5 class="modal-title font-weight-bold">Detail Pasien: {{ $pasien->nama }}</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <!-- Header Info -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex align-items-start">
                <div class="mr-3">
                    <div class="bg-light rounded-circle d-flex align-items-center justify-content-center text-primary"
                        style="width: 50px; height: 50px;">
                        <i class="fas fa-user fa-lg"></i>
                    </div>
                </div>
                <div class="flex-grow-1">
                    <div class="row">
                        <div class="col-md-4">
                            <h6 class="font-weight-bold mb-0 text-dark">{{ $pasien->nama }}</h6>
                            <small class="text-muted">ID: {{ str_pad($pasien->id, 4, '0', STR_PAD_LEFT) }}</small>
                            <div class="mt-2 text-sm text-secondary">
                                <i class="fas fa-map-marker-alt mr-2"></i> {{ $pasien->alamat ?? '-' }}
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-sm text-secondary mb-1">
                                <i class="fas fa-phone mr-2"></i> {{ $pasien->no_hp ?? '-' }}
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-sm text-secondary mb-1">
                                <i class="fas fa-gift mr-2"></i>
                                {{ $pasien->tgl_lahir ? $pasien->tgl_lahir->format('d/m/Y') : '-' }}
                            </div>
                            <small class="text-muted ml-4">{{ $pasien->usia }}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <hr>

    <!-- Resep Terakhir -->
    <h6 class="font-weight-bold text-primary mb-3">
        <i class="fas fa-eye mr-2"></i> Resep Terakhir
        ({{ $pasien->last_exam_date ? \Carbon\Carbon::parse($pasien->last_exam_date)->format('d/m/Y') : '-' }})
    </h6>

    <div class="card bg-light border-0 mb-4">
        <div class="card-body p-3">
            <div class="row text-center text-muted text-xs font-weight-bold text-uppercase mb-2">
                <div class="col-2"></div>
                <div class="col">SPH</div>
                <div class="col">CYL</div>
                <div class="col">PD</div>
            </div>
            <!-- OD (Right) -->
            <div class="row align-items-center mb-2">
                <div class="col-2 text-right font-weight-bold text-dark">OD</div>
                <div class="col">
                    <div class="bg-white p-2 rounded shadow-sm font-weight-bold text-dark">
                        {{ $pasien->sph_r ?? '-' }}
                    </div>
                </div>
                <div class="col">
                    <div class="bg-white p-2 rounded shadow-sm font-weight-bold text-dark">
                        {{ $pasien->cyl_r ?? '-' }}
                    </div>
                </div>
                <div class="col rowspan-2 align-middle">
                    <!-- PD often valid for both or single, simplify layout -->
                    <div class="bg-white p-2 rounded shadow-sm font-weight-bold text-dark">
                        {{ $pasien->pd ?? '-' }} <span class="text-xs text-muted font-weight-normal">mm</span>
                    </div>
                </div>
            </div>
            <!-- OS (Left) -->
            <div class="row align-items-center">
                <div class="col-2 text-right font-weight-bold text-dark">OS</div>
                <div class="col">
                    <div class="bg-white p-2 rounded shadow-sm font-weight-bold text-dark">
                        {{ $pasien->sph_l ?? '-' }}
                    </div>
                </div>
                <div class="col">
                    <div class="bg-white p-2 rounded shadow-sm font-weight-bold text-dark">
                        {{ $pasien->cyl_l ?? '-' }}
                    </div>
                </div>
                <div class="col">
                    <!-- Placeholder to align grid -->
                </div>
            </div>
        </div>
    </div>

    <!-- Riwayat Pemeriksaan -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h6 class="font-weight-bold text-primary mb-0">
            <i class="fas fa-calendar-alt mr-2"></i> Riwayat Pemeriksaan
        </h6>
        <a href="{{ route('pasien.print', $pasien->id) }}" target="_blank"
            class="btn btn-primary btn-sm btn-icon-split">
            <span class="icon text-white-50">
                <i class="fas fa-print"></i>
            </span>
            <span class="text">Unduh Riwayat</span>
        </a>
    </div>

    <div class="card border mb-3">
        <div class="card-body p-3">
            <!-- History List -->
            <div class="history-list">
                @forelse($pasien->riwayatPemeriksaans as $riwayat)
                    <div class="card mb-3 border shadow-sm">
                        <div class="card-body p-3">
                            <div class="row align-items-center">
                                <!-- Date -->
                                <div class="col-md-3 mb-2 mb-md-0">
                                    <div class="font-weight-bold text-dark">
                                        {{ $riwayat->created_at->translatedFormat('d F Y') }}
                                    </div>
                                    <small class="text-muted">
                                        {{ $riwayat->created_at->format('H:i') }}
                                    </small>
                                </div>

                                <!-- OD -->
                                <div class="col-md-3 mb-2 mb-md-0">
                                    <span class="text-xs text-muted font-weight-bold d-block">OD (Kanan)</span>
                                    <span class="font-weight-bold text-dark">
                                        {{ $riwayat->sph_r ?? '-' }} / {{ $riwayat->cyl_r ?? '-' }}
                                    </span>
                                </div>

                                <!-- OS -->
                                <div class="col-md-3 mb-2 mb-md-0">
                                    <span class="text-xs text-muted font-weight-bold d-block">OS (Kiri)</span>
                                    <span class="font-weight-bold text-dark">
                                        {{ $riwayat->sph_l ?? '-' }} / {{ $riwayat->cyl_l ?? '-' }}
                                    </span>
                                </div>

                                <!-- PD -->
                                <div class="col-md-3 text-md-right">
                                    <span class="text-xs text-muted font-weight-bold d-block">PD</span>
                                    <span class="font-weight-bold text-dark">
                                        {{ $riwayat->pd ?? '-' }} mm
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-4 border rounded bg-light">
                        <p class="text-muted mb-0">Belum ada riwayat pemeriksaan.</p>
                    </div>
                @endforelse
            </div>

            <!-- We assume standard transactions don't carry full prescription history in this iteration 
                 as per the schema limitations agreed upon. -->
        </div>
    </div>
</div>
<div class="modal-footer border-0 pt-0">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
</div>