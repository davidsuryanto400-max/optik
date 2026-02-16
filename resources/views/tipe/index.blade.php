@extends('layouts.app')

@section('title', 'Manajemen Tipe Produk')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 font-weight-bold">Manajemen Tipe Produk</h5>
                        <div class="card-tools d-flex align-items-center">
                            <form action="{{ route('tipe.index') }}" method="GET" class="mr-3">
                                <div class="input-group input-group-sm" style="width: 250px;">
                                    <input type="text" name="search" class="form-control" placeholder="Cari nama tipe..."
                                        value="{{ request('search') }}">
                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-default">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </form>
                            <button type="button" class="btn btn-primary btn-sm" data-toggle="modal"
                                data-target="#modalTambahTipe">
                                <i class="fas fa-plus mr-1"></i> Tambah Tipe
                            </button>
                        </div>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body p-0">
                    <table class="table table-hover table-striped text-nowrap">
                        <thead class="bg-light">
                            <tr>
                                <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 ps-4">ID Tipe
                                </th>
                                <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 ps-2">Nama
                                    Tipe</th>
                                <th class="text-secondary opacity-7"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($tipes as $tipe)
                                <tr>
                                    <td class="align-middle text-sm">
                                        <span
                                            class="text-secondary font-weight-bold ml-2">TYP-{{ str_pad($tipe->id, 3, '0', STR_PAD_LEFT) }}</span>
                                    </td>
                                    <td class="align-middle text-sm">
                                        <span class="font-weight-bold text-dark">{{ $tipe->nama }}</span>
                                    </td>
                                    <td class="align-middle text-right">
                                        <button type="button" class="btn btn-link text-primary btn-sm btn-edit-tipe"
                                            data-id="{{ $tipe->id }}" data-nama="{{ $tipe->nama }}" data-toggle="modal"
                                            data-target="#modalEditTipe">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <form action="{{ route('tipe.destroy', $tipe->id) }}" method="POST"
                                            style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-link text-danger btn-sm"
                                                onclick="return confirm('Yakin ingin menghapus tipe ini?')"><i
                                                    class="fas fa-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center py-4">
                                        <p class="text-muted mb-0">Data tidak ditemukan.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <!-- /.card-body -->
                <div class="card-footer bg-white clearfix">
                    {{ $tipes->withQueryString()->links('pagination::bootstrap-4') }}
                </div>
            </div>
            <!-- /.card -->
        </div>
    </div>

    <!-- Modal Tambah Tipe -->
    <div class="modal fade" id="modalTambahTipe" tabindex="-1" role="dialog" aria-labelledby="modalTambahTipeLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title font-weight-bold" id="modalTambahTipeLabel">Tambah Tipe Baru</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('tipe.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="nama" class="font-weight-bold text-muted text-sm">Nama Tipe</label>
                            <input type="text" class="form-control py-4 border-light bg-light" id="nama" name="nama"
                                placeholder="Contoh: Frame, Lensa, Softlens" required>
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

    <!-- Modal Edit Tipe -->
    <div class="modal fade" id="modalEditTipe" tabindex="-1" role="dialog" aria-labelledby="modalEditTipeLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title font-weight-bold" id="modalEditTipeLabel">Edit Tipe</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="formEditTipe" action="" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="nama_edit" class="font-weight-bold text-muted text-sm">Nama Tipe</label>
                            <input type="text" class="form-control py-4 border-light bg-light" id="nama_edit" name="nama"
                                required>
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
        .table th {
            border-top: 0;
        }

        .form-control:focus {
            box-shadow: none;
            border-color: #007bff;
        }
    </style>
@endpush

@push('scripts')
    <script>
        $(document).ready(function () {
            $('.btn-edit-tipe').on('click', function () {
                var id = $(this).data('id');
                var nama = $(this).data('nama');
                var url = "{{ route('tipe.index') }}/" + id;

                $('#formEditTipe').attr('action', url);
                $('#nama_edit').val(nama);
            });
        });
    </script>
@endpush