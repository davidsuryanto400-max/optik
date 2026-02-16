@extends('layouts.app')

@section('title', 'Manajemen Gudang')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 font-weight-bold">Manajemen Gudang</h5>
                        <div class="card-tools d-flex align-items-center">
                            <form action="{{ route('gudang.index') }}" method="GET" class="mr-3">
                                <div class="input-group input-group-sm" style="width: 250px;">
                                    <input type="text" name="search" class="form-control"
                                        placeholder="Cari nama gudang/cabang..." value="{{ request('search') }}">
                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-default">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </form>
                            <button type="button" class="btn btn-primary btn-sm" data-toggle="modal"
                                data-target="#modalTambahGudang">
                                <i class="fas fa-plus mr-1"></i> Tambah Gudang
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

                    @forelse($cabangs as $cabang)
                        <div class="card mb-2 border-0 shadow-none">
                            <div class="card-header bg-white py-2" id="heading{{ $cabang->id }}" data-toggle="collapse"
                                data-target="#collapse{{ $cabang->id }}" aria-expanded="true"
                                aria-controls="collapse{{ $cabang->id }}" style="cursor: pointer;">
                                <h6 class="mb-0 text-primary font-weight-bold">
                                    <i class="fas fa-chevron-right mr-2 transition-icon"></i>
                                    {{ $cabang->nama }}
                                </h6>
                            </div>

                            <div id="collapse{{ $cabang->id }}" class="collapse show multi-collapse"
                                aria-labelledby="heading{{ $cabang->id }}">
                                <div class="card-body p-0">
                                    <table class="table table-hover table-sm text-nowrap mb-0">
                                        <tbody>
                                            @foreach($cabang->gudangs as $gudang)
                                                <tr>
                                                    <td class="pl-4 align-middle" style="width: 15%;">
                                                        <span
                                                            class="text-secondary text-xs">GDG-{{ str_pad($gudang->id, 3, '0', STR_PAD_LEFT) }}</span>
                                                    </td>
                                                    <td class="align-middle">
                                                        <span class="text-dark font-weight-normal">{{ $gudang->nama }}</span>
                                                    </td>
                                                    <td class="align-middle text-right pr-3">
                                                        <button type="button"
                                                            class="btn btn-link text-primary btn-sm btn-edit-gudang p-0 mr-2"
                                                            data-id="{{ $gudang->id }}" data-nama="{{ $gudang->nama }}"
                                                            data-cabang_id="{{ $gudang->cabang_id }}" data-toggle="modal"
                                                            data-target="#modalEditGudang">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                        <form action="{{ route('gudang.destroy', $gudang->id) }}" method="POST"
                                                            style="display:inline;">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-link text-danger btn-sm p-0"
                                                                onclick="return confirm('Yakin ingin menonaktifkan gudang ini?')"><i
                                                                    class="fas fa-trash"></i></button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            @if($cabang->gudangs->isEmpty())
                                                <tr>
                                                    <td colspan="3" class="text-center text-muted text-xs py-2">
                                                        <em>Tidak ada gudang aktif di cabang ini.</em>
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
                    {{ $cabangs->withQueryString()->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Gudang -->
    <div class="modal fade" id="modalTambahGudang" tabindex="-1" role="dialog" aria-labelledby="modalTambahGudangLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title font-weight-bold" id="modalTambahGudangLabel">Tambah Gudang Baru</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('gudang.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="nama" class="font-weight-bold text-muted text-sm">Nama Gudang</label>
                            <input type="text" class="form-control py-4 border-light bg-light" id="nama" name="nama"
                                placeholder="Contoh: Gudang Pusat" required>
                        </div>
                        <div class="form-group">
                            <label for="cabang_id" class="font-weight-bold text-muted text-sm">Cabang</label>
                            <select class="form-control border-light bg-light" id="cabang_id" name="cabang_id" required>
                                <option value="">Pilih Cabang</option>
                                @foreach($all_cabangs as $c)
                                    <option value="{{ $c->id }}">{{ $c->nama }}</option>
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

    <!-- Modal Edit Gudang -->
    <div class="modal fade" id="modalEditGudang" tabindex="-1" role="dialog" aria-labelledby="modalEditGudangLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title font-weight-bold" id="modalEditGudangLabel">Edit Gudang</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="formEditGudang" action="" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="nama_edit" class="font-weight-bold text-muted text-sm">Nama Gudang</label>
                            <input type="text" class="form-control py-4 border-light bg-light" id="nama_edit" name="nama"
                                required>
                        </div>
                        <div class="form-group">
                            <label for="cabang_id_edit" class="font-weight-bold text-muted text-sm">Cabang</label>
                            <select class="form-control border-light bg-light" id="cabang_id_edit" name="cabang_id"
                                required>
                                <option value="">Pilih Cabang</option>
                                @foreach($all_cabangs as $c)
                                    <option value="{{ $c->id }}">{{ $c->nama }}</option>
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
            $('.btn-edit-gudang').on('click', function () {
                var id = $(this).data('id');
                var nama = $(this).data('nama');
                var cabang_id = $(this).data('cabang_id');
                var url = "{{ route('gudang.index') }}/" + id;

                $('#formEditGudang').attr('action', url);
                $('#nama_edit').val(nama);
                $('#cabang_id_edit').val(cabang_id);
            });

            // Custom Collapse Logic if needed (Bootstrap handles basic)
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