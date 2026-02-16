@extends('layouts.app')

@section('title', 'Manajemen Kategori')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 font-weight-bold">Manajemen Kategori</h5>
                        <div class="card-tools d-flex align-items-center">
                            <form action="{{ route('kategori.index') }}" method="GET" class="mr-3">
                                <div class="input-group input-group-sm" style="width: 250px;">
                                    <input type="text" name="search" class="form-control"
                                        placeholder="Cari nama kategori/tipe..." value="{{ request('search') }}">
                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-default">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </form>
                            <button type="button" class="btn btn-primary btn-sm" data-toggle="modal"
                                data-target="#modalTambahKategori">
                                <i class="fas fa-plus mr-1"></i> Tambah Kategori
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
                                    <table class="table table-hover table-sm text-nowrap mb-0">
                                        <tbody>
                                            @foreach($tipe->kategoris as $kategori)
                                                <tr>
                                                    <td class="pl-4 align-middle" style="width: 15%;">
                                                        <span
                                                            class="text-secondary text-xs">CAT-{{ str_pad($kategori->id, 3, '0', STR_PAD_LEFT) }}</span>
                                                    </td>
                                                    <td class="align-middle">
                                                        <span class="text-dark font-weight-normal">{{ $kategori->nama }}</span>
                                                    </td>
                                                    <td class="align-middle text-right pr-3">
                                                        <button type="button"
                                                            class="btn btn-link text-primary btn-sm btn-edit-kategori p-0 mr-2"
                                                            data-id="{{ $kategori->id }}" data-nama="{{ $kategori->nama }}"
                                                            data-tipe_id="{{ $kategori->tipe_id }}" data-toggle="modal"
                                                            data-target="#modalEditKategori">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                        <form action="{{ route('kategori.destroy', $kategori->id) }}" method="POST"
                                                            style="display:inline;">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-link text-danger btn-sm p-0"
                                                                onclick="return confirm('Yakin ingin menghapus kategori ini?')"><i
                                                                    class="fas fa-trash"></i></button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            @if($tipe->kategoris->isEmpty())
                                                <tr>
                                                    <td colspan="3" class="text-center text-muted text-xs py-2">
                                                        <em>Tidak ada kategori di tipe ini.</em>
                                                    </td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
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

    <!-- Modal Tambah Kategori -->
    <div class="modal fade" id="modalTambahKategori" tabindex="-1" role="dialog" aria-labelledby="modalTambahKategoriLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title font-weight-bold" id="modalTambahKategoriLabel">Tambah Kategori Baru</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('kategori.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="nama" class="font-weight-bold text-muted text-sm">Nama Kategori</label>
                            <input type="text" class="form-control py-4 border-light bg-light" id="nama" name="nama"
                                placeholder="Contoh: Metal, Plastik, Harian" required>
                        </div>
                        <div class="form-group">
                            <label for="tipe_id" class="font-weight-bold text-muted text-sm">Tipe Produk</label>
                            <select class="form-control border-light bg-light" id="tipe_id" name="tipe_id" required>
                                <option value="">Pilih Tipe</option>
                                @foreach($all_tipes as $t)
                                    <option value="{{ $t->id }}">{{ $t->nama }}</option>
                                @endforeach
                            </select>
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

    <!-- Modal Edit Kategori -->
    <div class="modal fade" id="modalEditKategori" tabindex="-1" role="dialog" aria-labelledby="modalEditKategoriLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title font-weight-bold" id="modalEditKategoriLabel">Edit Kategori</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="formEditKategori" action="" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="nama_edit" class="font-weight-bold text-muted text-sm">Nama Kategori</label>
                            <input type="text" class="form-control py-4 border-light bg-light" id="nama_edit" name="nama"
                                required>
                        </div>
                        <div class="form-group">
                            <label for="tipe_id_edit" class="font-weight-bold text-muted text-sm">Tipe Produk</label>
                            <select class="form-control border-light bg-light" id="tipe_id_edit" name="tipe_id" required>
                                <option value="">Pilih Tipe</option>
                                @foreach($all_tipes as $t)
                                    <option value="{{ $t->id }}">{{ $t->nama }}</option>
                                @endforeach
                            </select>
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
    </style>
@endpush

@push('scripts')
    <script>
        $(document).ready(function () {
            $('.btn-edit-kategori').on('click', function () {
                var id = $(this).data('id');
                var nama = $(this).data('nama');
                var tipe_id = $(this).data('tipe_id');
                var url = "{{ route('kategori.index') }}/" + id;

                $('#formEditKategori').attr('action', url);
                $('#nama_edit').val(nama);
                $('#tipe_id_edit').val(tipe_id);
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