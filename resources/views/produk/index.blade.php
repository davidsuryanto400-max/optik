@extends('layouts.app')

@section('title', 'Manajemen Produk')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 font-weight-bold">Manajemen Produk</h5>
                        <div class="card-tools d-flex align-items-center">
                            <form action="{{ route('produk.index') }}" method="GET" class="mr-3">
                                <div class="input-group input-group-sm" style="width: 250px;">
                                    <input type="text" name="search" class="form-control"
                                        placeholder="Cari nama, merek, gudang..." value="{{ request('search') }}">
                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-default">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </form>
                            <button type="button" class="btn btn-primary btn-sm" data-toggle="modal"
                                data-target="#modalTambahProduk">
                                <i class="fas fa-plus mr-1"></i> Tambah Produk
                            </button>
                        </div>
                    </div>
                </div>

                <div class="card-body bg-light p-3">
                    <div class="d-flex justify-content-end mb-2">
                        <button class="btn btn-default btn-xs mr-2 btn-expand-all" type="button">
                            <i class="fas fa-angle-double-down mr-1"></i> Buka Semua
                        </button>
                        <button class="btn btn-default btn-xs btn-collapse-all" type="button">
                            <i class="fas fa-angle-double-up mr-1"></i> Tutup Semua
                        </button>
                    </div>

                    <div
                        class="row text-uppercase text-secondary text-xs font-weight-bolder opacity-7 mb-2 px-3 d-none d-md-flex">
                        <div class="col" style="flex: 2;">Nama Produk</div>
                        <div class="col">Merek</div>
                        <div class="col">Gudang</div>
                        <div class="col">Kategori</div>
                        <div class="col">Harga</div>
                        <div class="col text-center">Stok</div>
                        <div class="col text-right">Aksi</div>
                    </div>

                    @forelse($tipes as $tipe)
                        <div class="card mb-2 border-0 shadow-none">
                            <div class="card-header bg-white py-2" id="heading{{ $tipe->id }}" data-toggle="collapse"
                                data-target="#collapse{{ $tipe->id }}" aria-expanded="true"
                                aria-controls="collapse{{ $tipe->id }}" style="cursor: pointer;">
                                <h6 class="mb-0 text-primary font-weight-bold">
                                    <i class="fas fa-chevron-right mr-2 transition-icon"></i>
                                    {{ $tipe->nama }}
                                </h6>
                            </div>

                            <div id="collapse{{ $tipe->id }}" class="collapse show multi-collapse"
                                aria-labelledby="heading{{ $tipe->id }}">
                                <div class="card-body p-0">
                                    <div class="list-group list-group-flush">
                                        @foreach($tipe->produks as $produk)
                                            <div class="list-group-item px-3 py-2 border-bottom-0">
                                                <div class="row align-items-center">
                                                    <div class="col" style="flex: 2;">
                                                        <div class="font-weight-bold text-dark">{{ $produk->nama }}</div>
                                                        <div class="small text-muted">{{ $produk->kode ?? 'N/A' }}</div>
                                                    </div>
                                                    <div class="col text-sm text-dark">{{ $produk->merek ?? '-' }}</div>
                                                    <div class="col text-sm text-dark">{{ $produk->gudang->nama ?? '-' }}</div>
                                                    <div class="col text-sm text-dark">{{ $produk->kategori->nama ?? '-' }}</div>
                                                    <div class="col text-sm text-dark">Rp
                                                        {{ number_format($produk->harga_jual, 0, ',', '.') }}
                                                    </div>
                                                    <div class="col text-center">
                                                        @php
                                                            $badgeClass = 'success';
                                                            if ($produk->stok < $produk->stok_minimum) {
                                                                $badgeClass = 'danger';
                                                            } elseif ($produk->stok == $produk->stok_minimum) {
                                                                $badgeClass = 'warning';
                                                            }
                                                        @endphp
                                                        <span class="badge badge-{{ $badgeClass }} px-2 py-1">
                                                            {{ $produk->stok }}
                                                        </span>
                                                    </div>
                                                    <div class="col text-right">
                                                        <button type="button"
                                                            class="btn btn-link text-secondary btn-sm p-0 mr-2 btn-show-produk"
                                                            data-id="{{ $produk->id }}" title="Lihat Detail">
                                                            <i class="fas fa-eye"></i>
                                                        </button>
                                                        <button type="button"
                                                            class="btn btn-link text-primary btn-sm btn-edit-produk p-0 mr-2"
                                                            data-id="{{ $produk->id }}" data-nama="{{ $produk->nama }}"
                                                            data-merek="{{ $produk->merek }}" data-tipe_id="{{ $produk->tipe_id }}"
                                                            data-kategori_id="{{ $produk->kategori_id }}"
                                                            data-warna="{{ $produk->warna }}"
                                                            data-gudang_id="{{ $produk->gudang_id }}"
                                                            data-harga_beli="{{ $produk->harga_beli }}"
                                                            data-harga_jual="{{ $produk->harga_jual }}"
                                                            data-stok="{{ $produk->stok }}"
                                                            data-stok_minimum="{{ $produk->stok_minimum }}"
                                                            data-kode="{{ $produk->kode }}" data-toggle="modal"
                                                            data-target="#modalEditProduk">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                        <form action="{{ route('produk.destroy', $produk->id) }}" method="POST"
                                                            style="display:inline;">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-link text-danger btn-sm p-0"
                                                                onclick="return confirm('Yakin ingin menghapus produk ini?')"><i
                                                                    class="fas fa-trash"></i></button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                            <hr class="m-0 border-light">
                                        @endforeach
                                        @if($tipe->produks->isEmpty())
                                            <div class="text-center text-muted text-xs py-2">
                                                <em>Tidak ada produk di tipe ini.</em>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="alert alert-light text-center" role="alert">
                            Data tidak ditemukan.
                        </div>
                    @endforelse
                </div>

                <div class="card-footer bg-white clearfix">
                    {{ $tipes->withQueryString()->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Produk -->
    <div class="modal fade" id="modalTambahProduk" tabindex="-1" role="dialog" aria-labelledby="modalTambahProdukLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title font-weight-bold" id="modalTambahProdukLabel">Tambah Produk Baru</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('produk.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label for="nama" class="font-weight-bold text-muted text-sm">Nama Produk</label>
                                <input type="text" class="form-control border-light bg-light" id="nama" name="nama"
                                    required>
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="merek" class="font-weight-bold text-muted text-sm">Merek</label>
                                <input type="text" class="form-control border-light bg-light" id="merek" name="merek">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label for="tipe_id" class="font-weight-bold text-muted text-sm">Tipe Produk</label>
                                <select class="form-control border-light bg-light" id="tipe_id" name="tipe_id" required>
                                    <option value="">Pilih Tipe</option>
                                    @foreach($all_tipes as $t)
                                        <option value="{{ $t->id }}">{{ $t->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="kategori_id" class="font-weight-bold text-muted text-sm">Kategori</label>
                                <select class="form-control border-light bg-light" id="kategori_id" name="kategori_id"
                                    required>
                                    <option value="">Pilih Kategori</option>
                                    @foreach($all_kategoris as $k)
                                        <option value="{{ $k->id }}">{{ $k->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label for="warna" class="font-weight-bold text-muted text-sm">Warna</label>
                                <input type="text" class="form-control border-light bg-light" id="warna" name="warna"
                                    placeholder="Contoh: Hitam">
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="gudang_id" class="font-weight-bold text-muted text-sm">Gudang</label>
                                <select class="form-control border-light bg-light" id="gudang_id" name="gudang_id" required>
                                    <option value="">Pilih Gudang</option>
                                    @foreach($all_gudangs as $g)
                                        <option value="{{ $g->id }}">{{ $g->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 form-group">
                                <label for="harga_jual" class="font-weight-bold text-muted text-sm">Harga Jual (Rp)</label>
                                <input type="number" class="form-control border-light bg-light" id="harga_jual"
                                    name="harga_jual" required>
                                <!-- Hidden purchase price for now, default to same or 0 if user didn't specify in request -->
                                <input type="hidden" name="harga_beli" value="0">
                            </div>
                            <div class="col-md-4 form-group">
                                <label for="stok" class="font-weight-bold text-muted text-sm">Stok Awal</label>
                                <input type="number" class="form-control border-light bg-light" id="stok" name="stok"
                                    value="0" required>
                            </div>
                            <div class="col-md-4 form-group">
                                <label for="stok_minimum" class="font-weight-bold text-muted text-sm">Stok Minimum</label>
                                <input type="number" class="form-control border-light bg-light" id="stok_minimum"
                                    name="stok_minimum" value="10" required>
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

    <!-- Modal Edit Produk -->
    <div class="modal fade" id="modalEditProduk" tabindex="-1" role="dialog" aria-labelledby="modalEditProdukLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title font-weight-bold" id="modalEditProdukLabel">Edit Produk</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="formEditProduk" action="" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label for="nama_edit" class="font-weight-bold text-muted text-sm">Nama Produk</label>
                                <input type="text" class="form-control border-light bg-light" id="nama_edit" name="nama"
                                    required>
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="merek_edit" class="font-weight-bold text-muted text-sm">Merek</label>
                                <input type="text" class="form-control border-light bg-light" id="merek_edit" name="merek">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label for="tipe_id_edit" class="font-weight-bold text-muted text-sm">Tipe Produk</label>
                                <select class="form-control border-light bg-light" id="tipe_id_edit" name="tipe_id"
                                    required>
                                    <option value="">Pilih Tipe</option>
                                    @foreach($all_tipes as $t)
                                        <option value="{{ $t->id }}">{{ $t->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="kategori_id_edit" class="font-weight-bold text-muted text-sm">Kategori</label>
                                <select class="form-control border-light bg-light" id="kategori_id_edit" name="kategori_id"
                                    required>
                                    <option value="">Pilih Kategori</option>
                                    @foreach($all_kategoris as $k)
                                        <option value="{{ $k->id }}">{{ $k->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label for="warna_edit" class="font-weight-bold text-muted text-sm">Warna</label>
                                <input type="text" class="form-control border-light bg-light" id="warna_edit" name="warna">
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="gudang_id_edit" class="font-weight-bold text-muted text-sm">Gudang</label>
                                <select class="form-control border-light bg-light" id="gudang_id_edit" name="gudang_id"
                                    required>
                                    <option value="">Pilih Gudang</option>
                                    @foreach($all_gudangs as $g)
                                        <option value="{{ $g->id }}">{{ $g->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label for="harga_jual_edit" class="font-weight-bold text-muted text-sm">Harga (Rp)</label>
                                <input type="number" class="form-control border-light bg-light" id="harga_jual_edit"
                                    name="harga_jual" required>
                                <input type="hidden" id="harga_beli_edit" name="harga_beli">
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="stok_minimum_edit" class="font-weight-bold text-muted text-sm">Stok
                                    Minimum</label>
                                <input type="number" class="form-control border-light bg-light" id="stok_minimum_edit"
                                    name="stok_minimum" required>
                                <input type="hidden" id="stok_edit" name="stok">
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

    <!-- Modal Detail Produk -->
    <div class="modal fade" id="modalDetailProduk" tabindex="-1" role="dialog" aria-labelledby="modalDetailProdukLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header border-0 pb-0">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body pt-0" id="modalDetailContent">
                    <div class="text-center py-5">
                        <div class="spinner-border text-primary" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .form-control:focus {
            box-shadow: none;
            border-color: #007bff;
        }

        .transition-icon {
            transition: transform 0.3s;
        }

        [aria-expanded="true"] .transition-icon {
            transform: rotate(90deg);
        }

        .list-group-item:last-child {
            border-bottom: 0;
        }
    </style>
@endpush

@push('scripts')
    <script>
        $(document).ready(function () {
            // Show Detail
            $('.btn-show-produk').on('click', function () {
                var id = $(this).data('id');
                var url = "{{ route('produk.index') }}/" + id;

                $('#modalDetailProduk').modal('show');
                $('#modalDetailContent').html('<div class="text-center py-5"><div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div></div>');

                $.get(url, function (data) {
                    $('#modalDetailContent').html(data);
                }).fail(function (jqXHR, textStatus, errorThrown) {
                    var errorMsg = errorThrown;
                    try {
                        var json = JSON.parse(jqXHR.responseText);
                        if (json.error) errorMsg = json.error;
                        else if (json.message) errorMsg = json.message;
                    } catch (e) {
                        // fallback
                    }
                    $('#modalDetailContent').html('<div class="text-center py-5 text-danger"><i class="fas fa-exclamation-triangle fa-2x mb-3"></i><br><strong>Gagal memuat data!</strong><br>' + errorMsg + '</div>');
                    console.error("Error loading details:", jqXHR.responseText);
                });
            });

            $('.btn-edit-produk').on('click', function () {
                var id = $(this).data('id');
                var nama = $(this).data('nama');
                var merek = $(this).data('merek');
                var tipe_id = $(this).data('tipe_id');
                var kategori_id = $(this).data('kategori_id');
                var warna = $(this).data('warna');
                var gudang_id = $(this).data('gudang_id');
                var harga_beli = $(this).data('harga_beli');
                var harga_jual = $(this).data('harga_jual');
                var stok = $(this).data('stok');
                var stok_minimum = $(this).data('stok_minimum');
                var url = "{{ route('produk.index') }}/" + id;

                $('#formEditProduk').attr('action', url);
                $('#nama_edit').val(nama);
                $('#merek_edit').val(merek);
                $('#tipe_id_edit').val(tipe_id);
                $('#kategori_id_edit').val(kategori_id);
                $('#warna_edit').val(warna);
                $('#gudang_id_edit').val(gudang_id);
                $('#harga_beli_edit').val(harga_beli);
                $('#harga_jual_edit').val(harga_jual);
                $('#stok_edit').val(stok);
                $('#stok_minimum_edit').val(stok_minimum);
            });

            // Custom Collapse Logic
            $('.btn-expand-all').on('click', function () {
                $('.multi-collapse').collapse('show');
            });

            $('.btn-collapse-all').on('click', function () {
                $('.multi-collapse').collapse('hide');
            });
        });
    </script>
@endpush