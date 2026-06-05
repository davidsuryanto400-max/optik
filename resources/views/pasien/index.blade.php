@extends('layouts.app')

@section('title', 'Manajemen Pasien')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 font-weight-bold">Manajemen Pasien</h5>
                        <div class="card-tools d-flex align-items-center">
                            <form action="{{ route('pasien.index') }}" method="GET" class="mr-3">
                                <div class="input-group input-group-sm" style="width: 300px;">
                                    <input type="text" name="search" class="form-control"
                                        placeholder="Cari nama atau telepon..." value="{{ request('search') }}">
                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-default">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </form>
                            <a href="{{ request('filter') == 'birthday' ? route('pasien.index') : route('pasien.index', ['filter' => 'birthday']) }}"
                                class="btn btn-outline-danger btn-sm mr-2 {{ request('filter') == 'birthday' ? 'active' : '' }}"
                                title="{{ request('filter') == 'birthday' ? 'Hapus Filter' : 'Ulang Tahun dalam 30 hari ke depan' }}">
                                <i class="fas fa-birthday-cake mr-1"></i> Ulang Tahun
                                <span class="badge badge-danger ml-1">{{ $upcomingBirthdaysCount }}</span>
                            </a>
                            <button type="button" class="btn btn-primary btn-sm" data-toggle="modal"
                                data-target="#modalTambahPasien">
                                <i class="fas fa-plus mr-1"></i> Tambah Pasien
                            </button>
                        </div>
                    </div>
                </div>

                <div class="card-body bg-light p-3">
                    @if(request('filter') == 'birthday')
                        <div class="alert alert-info border-0 mb-3">
                            <i class="fas fa-info-circle mr-1"></i> Menampilkan pasien yang akan berulang tahun dalam 30 hari ke
                            depan.
                            <a href="{{ route('pasien.index') }}" class="font-weight-bold text-info">Tampilkan Semua</a>
                        </div>
                    @endif

                    <div class="card border-0 shadow-none mb-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="border-0 text-muted text-xs font-weight-bolder text-uppercase pl-4">Nama
                                            Pasien</th>
                                        <th class="border-0 text-muted text-xs font-weight-bolder text-uppercase">Kontak
                                        </th>
                                        <th class="border-0 text-muted text-xs font-weight-bolder text-uppercase">Tanggal
                                            Lahir</th>
                                        <th class="border-0 text-muted text-xs font-weight-bolder text-uppercase">Periksa
                                            Terakhir</th>
                                        <th
                                            class="border-0 text-muted text-xs font-weight-bolder text-uppercase text-right pr-4">
                                            Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($pasiens as $pasien)
                                        <tr class="bg-white border-bottom">
                                            <td class="align-middle pl-4 py-3">
                                                <div class="font-weight-bold text-dark">{{ $pasien->nama }}</div>
                                                <div class="text-xs text-muted">ID:
                                                    {{ str_pad($pasien->id, 4, '0', STR_PAD_LEFT) }}</div>
                                            </td>
                                            <td class="align-middle">
                                                <div class="text-dark text-sm">{{ $pasien->no_hp ?? '-' }}</div>
                                                <div class="text-xs text-muted">{{ Str::limit($pasien->alamat, 30) ?? '-' }}
                                                </div>
                                            </td>
                                            <td class="align-middle">
                                                <div class="text-dark text-sm">
                                                    {{ $pasien->tgl_lahir ? $pasien->tgl_lahir->format('d F Y') : '-' }}
                                                    @if($pasien->tgl_lahir && \Carbon\Carbon::parse($pasien->tgl_lahir)->setYear(now()->year)->between(now()->startOfDay(), now()->addDays(30)->endOfDay()))
                                                        <i class="fas fa-gift text-danger ml-1" title="Ulang Tahun Dekat!"></i>
                                                    @endif
                                                </div>
                                                <div class="text-xs text-muted">{{ $pasien->usia }}</div>
                                            </td>
                                            <td class="align-middle">
                                                <span class="text-sm text-dark">{{ $pasien->periksa_terakhir }}</span>
                                            </td>
                                            <td class="align-middle text-right pr-4">
                                                <button type="button"
                                                    class="btn btn-link text-secondary btn-sm p-0 mr-2 btn-show-pasien"
                                                    data-id="{{ $pasien->id }}" title="Lihat Detail">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button type="button"
                                                    class="btn btn-link text-primary btn-sm p-0 mr-2 btn-edit-pasien"
                                                    data-id="{{ $pasien->id }}" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <form action="{{ route('pasien.destroy', $pasien->id) }}" method="POST"
                                                    class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-link text-danger btn-sm p-0"
                                                        onclick="return confirm('Yakin ingin menghapus pasien ini?')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-5">
                                                <div class="text-muted">
                                                    <i class="fas fa-user-slash fa-3x mb-3"></i>
                                                    <p class="mb-0">Data pasien tidak ditemukan.</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="card-footer bg-white clearfix">
                    {{ $pasiens->withQueryString()->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tambah/Edit Pasien -->
    <div class="modal fade" id="modalTambahPasien" tabindex="-1" role="dialog" aria-labelledby="modalPasienLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title font-weight-bold" id="modalPasienLabel">Tambah Pasien Baru</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="formPasien" action="{{ route('pasien.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="_method" id="formMethod" value="POST">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="nama" class="font-weight-bold text-muted text-sm">Nama</label>
                            <input type="text" class="form-control border-light bg-light" id="nama" name="nama" required>
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label for="no_hp" class="font-weight-bold text-muted text-sm">Telepon</label>
                                <input type="text" class="form-control border-light bg-light" id="no_hp" name="no_hp">
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="tgl_lahir" class="font-weight-bold text-muted text-sm">Tanggal Lahir</label>
                                <input type="date" class="form-control border-light bg-light" id="tgl_lahir" name="tgl_lahir">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="last_exam_date" class="font-weight-bold text-muted text-sm">Tanggal Periksa Terakhir</label>
                            <input type="date" class="form-control border-light bg-light" id="last_exam_date" name="last_exam_date" value="{{ date('Y-m-d') }}">
                        </div>
                        <div class="form-group">
                            <label for="alamat" class="font-weight-bold text-muted text-sm">Alamat</label>
                            <textarea class="form-control border-light bg-light" id="alamat" name="alamat" rows="2"></textarea>
                        </div>

                        <hr>
                        <h6 class="font-weight-bold mb-3">Resep Kacamata (Pemeriksaan Terakhir)</h6>
                        
                        <h6 class="font-weight-bold mb-3 mt-3">Mata Kanan (OD)</h6>
                        <div class="row">
                            <div class="col-md-3 form-group">
                                <label for="sph_r" class="font-weight-bold text-muted text-xs">SPH</label>
                                <input type="number" step="0.25" class="form-control border-light bg-light" id="sph_r" name="sph_r" placeholder="0.00">
                            </div>
                            <div class="col-md-3 form-group">
                                <label for="cyl_r" class="font-weight-bold text-muted text-xs">CYL</label>
                                <input type="number" step="0.25" class="form-control border-light bg-light" id="cyl_r" name="cyl_r" placeholder="0.00">
                            </div>
                            <div class="col-md-3 form-group">
                                <label for="ax_r" class="font-weight-bold text-muted text-xs">AX</label>
                                <input type="text" class="form-control border-light bg-light" id="ax_r" name="ax_r" placeholder="0">
                            </div>
                            <div class="col-md-3 form-group">
                                <label for="add_r" class="font-weight-bold text-muted text-xs">ADD</label>
                                <input type="text" class="form-control border-light bg-light" id="add_r" name="add_r" placeholder="+0.00">
                            </div>
                        </div>
                        <h6 class="font-weight-bold mb-3 mt-2">Mata Kiri (OS)</h6>
                        <div class="row">
                            <div class="col-md-3 form-group">
                                <label for="sph_l" class="font-weight-bold text-muted text-xs">SPH</label>
                                <input type="number" step="0.25" class="form-control border-light bg-light" id="sph_l" name="sph_l" placeholder="0.00">
                            </div>
                            <div class="col-md-3 form-group">
                                <label for="cyl_l" class="font-weight-bold text-muted text-xs">CYL</label>
                                <input type="number" step="0.25" class="form-control border-light bg-light" id="cyl_l" name="cyl_l" placeholder="0.00">
                            </div>
                            <div class="col-md-3 form-group">
                                <label for="ax_l" class="font-weight-bold text-muted text-xs">AX</label>
                                <input type="text" class="form-control border-light bg-light" id="ax_l" name="ax_l" placeholder="0">
                            </div>
                            <div class="col-md-3 form-group">
                                <label for="add_l" class="font-weight-bold text-muted text-xs">ADD</label>
                                <input type="text" class="form-control border-light bg-light" id="add_l" name="add_l" placeholder="+0.00">
                            </div>
                        </div>
                         <div class="row">
                            <div class="col-md-12 form-group">
                                <label for="pd" class="font-weight-bold text-muted text-xs">Pupillary Distance (PD)</label>
                                <input type="text" class="form-control border-light bg-light" id="pd" name="pd" placeholder="62">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0">
                        <button type="button" class="btn btn-outline-secondary px-4" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary px-4">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Modal Detail Pasien -->
    <div class="modal fade" id="modalDetailPasien" tabindex="-1" role="dialog" aria-labelledby="modalDetailLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content border-0 shadow-lg" id="modalDetailContent">
                <!-- Content loaded via AJAX -->
                <div class="text-center p-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            // Reset form when modal is closed/opened for adding
            $('#modalTambahPasien').on('show.bs.modal', function (event) {
                // Only reset if triggered by the Add button (which uses data-toggle)
                if (event.relatedTarget && !$(event.relatedTarget).hasClass('btn-edit-pasien')) {
                    $('#modalPasienLabel').text('Tambah Pasien Baru');
                    $('#formPasien').attr('action', "{{ route('pasien.store') }}");
                    $('#formMethod').val('POST');
                    $('#formPasien')[0].reset();
                    $('#last_exam_date').val(new Date().toISOString().split('T')[0]);
                }
            });

            // Edit Pasien
            $(document).on('click', '.btn-edit-pasien', function () {
                var id = $(this).data('id');
                var url = "{{ route('pasien.index') }}/" + id; // This returns HTML by default now
                var updateUrl = "{{ route('pasien.index') }}/" + id;

                // Set Modal Title and Form Action
                $('#modalPasienLabel').text('Edit Pasien');
                $('#formPasien').attr('action', updateUrl);
                $('#formMethod').val('PUT');

                // Fetch Data (Force JSON for Edit)
                $.ajax({
                    url: url,
                    dataType: 'json',
                    success: function (data) {
                        $('#nama').val(data.nama);
                        $('#no_hp').val(data.no_hp);
                        $('#tgl_lahir').val(data.tgl_lahir ? data.tgl_lahir.split('T')[0] : '');
                        $('#alamat').val(data.alamat);
                        $('#last_exam_date').val(data.last_exam_date ? data.last_exam_date.split('T')[0] : '');
                        $('#sph_r').val(data.sph_r);
                        $('#cyl_r').val(data.cyl_r);
                        $('#ax_r').val(data.ax_r);
                        $('#add_r').val(data.add_r);
                        $('#sph_l').val(data.sph_l);
                        $('#cyl_l').val(data.cyl_l);
                        $('#ax_l').val(data.ax_l);
                        $('#add_l').val(data.add_l);
                        $('#pd').val(data.pd);

                        $('#modalTambahPasien').modal('show');
                    },
                    error: function () {
                        alert('Gagal mengambil data pasien.');
                    }
                });
            });

            // Show Pasien Detail
            $(document).on('click', '.btn-show-pasien', function () {
                var id = $(this).data('id');
                var url = "{{ route('pasien.index') }}/" + id;

                $('#modalDetailPasien').modal('show');
                $('#modalDetailContent').html('<div class="text-center p-5"><div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div></div>');

                // Fetch HTML
                $.get(url, function (data) {
                    $('#modalDetailContent').html(data);
                }).fail(function () {
                    $('#modalDetailContent').html('<div class="text-center p-5 text-danger">Gagal memuat data.</div>');
                });
            });
        });
    </script>
@endpush