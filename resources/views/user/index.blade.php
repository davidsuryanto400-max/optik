@extends('layouts.app')

@section('title', 'Manajemen User')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 font-weight-bold">
                            <i class="fas fa-users mr-2 text-primary"></i>Manajemen User
                        </h5>
                        <div class="card-tools d-flex align-items-center">
                            <form action="{{ route('user.index') }}" method="GET" class="d-flex mr-2">
                                <div class="input-group input-group-sm mr-2" style="width: 220px;">
                                    <input type="text" name="search" class="form-control"
                                        placeholder="Cari nama / username..."
                                        value="{{ request('search') }}">
                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-default">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                                <select name="role" class="form-control form-control-sm mr-2"
                                    onchange="this.form.submit()" style="width:160px;">
                                    <option value="">Semua Role</option>
                                    <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>User</option>
                                    <option value="administrator"
                                        {{ request('role') == 'administrator' ? 'selected' : '' }}>Administrator</option>
                                </select>
                            </form>
                            <button type="button" class="btn btn-primary btn-sm" data-toggle="modal"
                                data-target="#modalTambahUser">
                                <i class="fas fa-plus mr-1"></i> Tambah User
                            </button>
                        </div>
                    </div>
                </div>

                <div class="card-body p-0">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show mx-3 mt-3 mb-0" role="alert">
                            <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show mx-3 mt-3 mb-0" role="alert">
                            <i class="fas fa-exclamation-circle mr-1"></i> {{ session('error') }}
                            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                        </div>
                    @endif

                    <table class="table table-hover table-striped text-nowrap mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 pl-3">#</th>
                                <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Nama</th>
                                <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Username</th>
                                <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Role</th>
                                <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $user)
                                <tr>
                                    <td class="align-middle pl-3 text-sm text-muted">
                                        {{ ($users->currentPage() - 1) * $users->perPage() + $loop->iteration }}
                                    </td>
                                    <td class="align-middle">
                                        <div class="d-flex align-items-center">
                                            <div class="user-avatar mr-2 d-flex align-items-center justify-content-center"
                                                style="width:35px;height:35px;border-radius:50%;background:{{ $user->role == 'administrator' ? '#1a73e8' : '#6c757d' }};color:#fff;font-weight:700;font-size:14px;flex-shrink:0;">
                                                {{ strtoupper(substr($user->name, 0, 1)) }}
                                            </div>
                                            <span class="font-weight-bold text-dark">{{ $user->name }}</span>
                                        </div>
                                    </td>
                                    <td class="align-middle text-sm">
                                        <code class="bg-light px-2 py-1 rounded">{{ $user->username }}</code>
                                    </td>
                                    <td class="align-middle">
                                        @if($user->role == 'administrator')
                                            <span class="badge badge-primary px-3 py-1">
                                                <i class="fas fa-user-shield mr-1"></i>Administrator
                                            </span>
                                        @else
                                            <span class="badge badge-secondary px-3 py-1">
                                                <i class="fas fa-user mr-1"></i>User
                                            </span>
                                        @endif
                                    </td>
                                    <td class="align-middle text-center">
                                        <button type="button"
                                            class="btn btn-link text-primary btn-sm btn-edit-user p-1"
                                            data-id="{{ $user->id }}"
                                            data-name="{{ $user->name }}"
                                            data-username="{{ $user->username }}"
                                            data-role="{{ $user->role }}"
                                            data-toggle="modal"
                                            data-target="#modalEditUser"
                                            title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <form action="{{ route('user.destroy', $user->id) }}" method="POST"
                                            style="display:inline;" class="form-hapus-user">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-link text-danger btn-sm p-1"
                                                title="Nonaktifkan">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5">
                                        <i class="fas fa-users fa-2x text-muted mb-2 d-block"></i>
                                        <p class="text-muted mb-0">Data user tidak ditemukan.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="card-footer bg-white clearfix">
                    {{ $users->withQueryString()->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </div>
    </div>

    {{-- ===================== MODAL TAMBAH USER ===================== --}}
    <div class="modal fade" id="modalTambahUser" tabindex="-1" role="dialog" aria-labelledby="modalTambahUserLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title font-weight-bold" id="modalTambahUserLabel">
                        <i class="fas fa-user-plus mr-2 text-primary"></i>Tambah User Baru
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('user.store') }}" method="POST" id="formTambahUser">
                    @csrf
                    <input type="hidden" name="_form" value="tambah">
                    <div class="modal-body">

                        @if($errors->any() && old('_form') == 'tambah')
                            <div class="alert alert-danger">
                                <ul class="mb-0 pl-3">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="form-group">
                            <label class="font-weight-bold text-muted text-sm">Nama Lengkap <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control border-light bg-light" name="name"
                                placeholder="Masukkan nama lengkap" value="{{ old('name') }}" required>
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold text-muted text-sm">Username <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control border-light bg-light" name="username"
                                placeholder="Contoh: john_doe (huruf, angka, - _)" value="{{ old('username') }}"
                                required>
                            <small class="text-muted">Hanya huruf, angka, strip, dan underscore.</small>
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold text-muted text-sm">Role <span
                                    class="text-danger">*</span></label>
                            <select name="role" class="form-control border-light bg-light" required>
                                <option value="">-- Pilih Role --</option>
                                <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>User</option>
                                <option value="administrator"
                                    {{ old('role') == 'administrator' ? 'selected' : '' }}>Administrator</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold text-muted text-sm">Password <span
                                    class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="password" class="form-control border-light bg-light"
                                    name="password" id="password_tambah" placeholder="Minimal 8 karakter"
                                    required>
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-outline-secondary btn-toggle-pass"
                                        data-target="#password_tambah">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold text-muted text-sm">Konfirmasi Password <span
                                    class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="password" class="form-control border-light bg-light"
                                    name="password_confirmation" id="password_confirm_tambah"
                                    placeholder="Ulangi password" required>
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-outline-secondary btn-toggle-pass"
                                        data-target="#password_confirm_tambah">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0">
                        <button type="button" class="btn btn-outline-secondary px-4"
                            data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="fas fa-save mr-1"></i>Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ===================== MODAL EDIT USER ===================== --}}
    <div class="modal fade" id="modalEditUser" tabindex="-1" role="dialog" aria-labelledby="modalEditUserLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title font-weight-bold" id="modalEditUserLabel">
                        <i class="fas fa-user-edit mr-2 text-warning"></i>Edit User
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="formEditUser" action="" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="_form" value="edit">
                    <div class="modal-body">

                        @if($errors->any() && old('_form') == 'edit')
                            <div class="alert alert-danger">
                                <ul class="mb-0 pl-3">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="form-group">
                            <label class="font-weight-bold text-muted text-sm">Nama Lengkap <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control border-light bg-light" name="name"
                                id="edit_name" placeholder="Masukkan nama lengkap" required>
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold text-muted text-sm">Username <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control border-light bg-light" name="username"
                                id="edit_username" placeholder="Masukkan username" required>
                            <small class="text-muted">Hanya huruf, angka, strip, dan underscore.</small>
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold text-muted text-sm">Role <span
                                    class="text-danger">*</span></label>
                            <select name="role" id="edit_role" class="form-control border-light bg-light"
                                required>
                                <option value="user">User</option>
                                <option value="administrator">Administrator</option>
                            </select>
                        </div>

                        <hr class="my-2">
                        <p class="text-muted text-sm mb-2">
                            <i class="fas fa-info-circle mr-1"></i> Kosongkan password jika tidak ingin mengubah.
                        </p>

                        <div class="form-group">
                            <label class="font-weight-bold text-muted text-sm">Password Baru</label>
                            <div class="input-group">
                                <input type="password" class="form-control border-light bg-light"
                                    name="password" id="password_edit"
                                    placeholder="Minimal 8 karakter (opsional)">
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-outline-secondary btn-toggle-pass"
                                        data-target="#password_edit">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold text-muted text-sm">Konfirmasi Password Baru</label>
                            <div class="input-group">
                                <input type="password" class="form-control border-light bg-light"
                                    name="password_confirmation" id="password_confirm_edit"
                                    placeholder="Ulangi password baru">
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-outline-secondary btn-toggle-pass"
                                        data-target="#password_confirm_edit">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0">
                        <button type="button" class="btn btn-outline-secondary px-4"
                            data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-warning px-4 text-white">
                            <i class="fas fa-save mr-1"></i>Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        $(document).ready(function () {

            // Populate edit modal
            $('.btn-edit-user').on('click', function () {
                var id       = $(this).data('id');
                var name     = $(this).data('name');
                var username = $(this).data('username');
                var role     = $(this).data('role');
                var url      = "{{ url('user') }}/" + id;

                $('#formEditUser').attr('action', url);
                $('#edit_name').val(name);
                $('#edit_username').val(username);
                $('#edit_role').val(role);
                $('#password_edit').val('');
                $('#password_confirm_edit').val('');
            });

            // Toggle show/hide password
            $('.btn-toggle-pass').on('click', function () {
                var target = $($(this).data('target'));
                var icon   = $(this).find('i');
                if (target.attr('type') === 'password') {
                    target.attr('type', 'text');
                    icon.removeClass('fa-eye').addClass('fa-eye-slash');
                } else {
                    target.attr('type', 'password');
                    icon.removeClass('fa-eye-slash').addClass('fa-eye');
                }
            });

            // Confirm delete
            $('.form-hapus-user').on('submit', function (e) {
                e.preventDefault();
                var form = this;
                Swal.fire({
                    title: 'Nonaktifkan User?',
                    text: 'User akan dinonaktifkan dari sistem.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#e74c3c',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Nonaktifkan',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });

            // Auto-open modals if validation error
            @if($errors->any() && old('_form') == 'edit')
                $('#modalEditUser').modal('show');
            @endif
            @if($errors->any() && old('_form') == 'tambah')
                $('#modalTambahUser').modal('show');
            @endif
        });
    </script>
@endpush

@push('styles')
    <style>
        .table th { border-top: 0; }
        .form-control:focus { box-shadow: none; border-color: #007bff; }
        .badge { font-size: 0.78rem; border-radius: 20px; }
        .user-avatar { transition: transform .2s; }
        tr:hover .user-avatar { transform: scale(1.1); }
    </style>
@endpush
