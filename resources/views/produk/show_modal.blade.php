<div class="row">
    <div class="col-12">
        <h5 class="font-weight-bold text-dark mb-4">Detail Produk: {{ $produk->nama }}</h5>

        <div class="row mb-4">
            <div class="col-md-6">
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Merek</span>
                    <span class="font-weight-bold">{{ $produk->merek ?? '-' }}</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Tipe</span>
                    <span class="font-weight-bold">{{ optional($produk->tipe)->nama ?? '-' }}</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Kategori</span>
                    <span class="font-weight-bold">{{ optional($produk->kategori)->nama ?? '-' }}</span>
                </div>
                <div class="d-flex justify-content-between">
                    <span class="text-muted">Warna</span>
                    <span class="font-weight-bold">{{ $produk->warna ?? '-' }}</span>
                </div>
            </div>
            <div class="col-md-6 text-center">
                <div class="row">
                    <div class="col-4">
                        <div class="text-muted text-xs mb-1">Stok Saat Ini</div>
                        <div class="h4 font-weight-bold text-primary">{{ $produk->stok }}</div>
                    </div>
                    <div class="col-4">
                        <div class="text-muted text-xs mb-1">Stok Minimum</div>
                        <div class="h4 font-weight-bold text-dark">{{ $produk->stok_minimum }}</div>
                    </div>
                    <div class="col-4">
                        <div class="text-muted text-xs mb-1">Status</div>
                        @if($produk->stok < $produk->stok_minimum)
                            <span class="badge badge-danger px-3 py-2">Stock Rendah</span>
                        @elseif($produk->stok == $produk->stok_minimum)
                            <span class="badge badge-warning px-3 py-2">Menipis</span>
                        @else
                            <span class="badge badge-success px-3 py-2">Aman</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="card bg-light border-0 mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="font-weight-bold mb-0">Perbarui Stok</h6>
                    <small class="text-muted"><i class="fas fa-lock mr-1"></i> Kunci</small>
                </div>

                <div id="lockedState">
                    <div class="alert alert-light border d-flex align-items-center mb-3" role="alert">
                        <i class="fas fa-lock mr-3 text-muted" style="font-size: 1.5rem;"></i>
                        <div>
                            <h6 class="alert-heading font-weight-bold mb-1">Manajemen Stok (Akses Admin)</h6>
                            <p class="mb-0 text-muted text-xs">Masukkan password untuk mengubah stok produk.</p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="text-xs text-muted font-weight-bold text-uppercase">Password Admin</label>
                        <div class="input-group">
                            <input type="password" id="passwordInput"
                                class="form-control form-control-sm border-0 bg-light" placeholder="Password..."
                                required>
                            <div class="input-group-append">
                                <button class="btn btn-primary btn-sm font-weight-bold px-4" type="button"
                                    id="btnUnlock">
                                    Buka Kunci
                                </button>
                            </div>
                        </div>
                        <small id="passwordError" class="text-danger mt-1 d-none font-weight-bold"></small>
                    </div>
                </div>

                <div id="unlockedState" class="d-none">
                    <form id="formUpdateStok" action="{{ route('produk.updateStock', $produk->id) }}" method="POST">
                        @csrf
                        <input type="hidden" name="password" id="hiddenPassword">
                        <div class="row">
                            <div class="col-md-4 form-group">
                                <label class="text-xs text-muted font-weight-bold text-uppercase">Tipe Update</label>
                                <select name="tipe_update" class="form-control form-control-sm border-0 bg-light"
                                    required>
                                    <option value="masuk">Tambah Stok (+)</option>
                                    <option value="keluar">Kurangi Stok (-)</option>
                                </select>
                            </div>
                            <div class="col-md-3 form-group">
                                <label class="text-xs text-muted font-weight-bold text-uppercase">Jumlah</label>
                                <input type="number" name="jumlah"
                                    class="form-control form-control-sm border-0 bg-light" min="1" value="0" required>
                            </div>
                            <div class="col-md-5 form-group">
                                <label class="text-xs text-muted font-weight-bold text-uppercase">Catatan</label>
                                <input type="text" name="catatan" class="form-control form-control-sm border-0 bg-light"
                                    placeholder="Contoh: Stok awal">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block btn-sm font-weight-bold">Update
                            Stok</button>
                    </form>
                </div>

                <script>
                    $(document).ready(function () {
                        $('#btnUnlock').click(function () {
                            var password = $('#passwordInput').val();
                            var btn = $(this);

                            if (!password) {
                                $('#passwordError').text('Password harus diisi!').removeClass('d-none');
                                return;
                            }

                            btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');
                            $('#passwordError').addClass('d-none');

                            $.ajax({
                                url: '{{ route("produk.checkPassword") }}',
                                type: 'POST',
                                data: {
                                    _token: '{{ csrf_token() }}',
                                    password: password
                                },
                                success: function (response) {
                                    if (response.valid) {
                                        $('#hiddenPassword').val(password);
                                        $('#lockedState').addClass('d-none');
                                        $('#unlockedState').removeClass('d-none');
                                    }
                                },
                                error: function (xhr) {
                                    btn.prop('disabled', false).text('Buka Kunci');
                                    var msg = 'Terjadi kesalahan sistem';
                                    if (xhr.status === 401) {
                                        msg = xhr.responseJSON.message;
                                    }
                                    $('#passwordError').text(msg).removeClass('d-none');
                                }
                            });
                        });

                        // Allow enter key to submit password
                        $('#passwordInput').keypress(function (e) {
                            if (e.which == 13) {
                                $('#btnUnlock').click();
                                return false;
                            }
                        });
                    });
                </script>
            </div>
        </div>

        <h6 class="font-weight-bold mb-3">Riwayat Stok</h6>
        <div class="table-responsive" style="max-height: 250px; overflow-y: auto;">
            <table class="table table-sm table-hover text-sm mb-0">
                <thead class="bg-light sticky-top">
                    <tr>
                        <th class="border-0 text-muted">TANGGAL</th>
                        <th class="border-0 text-muted">TIPE</th>
                        <th class="border-0 text-muted">JUMLAH</th>
                        <th class="border-0 text-muted">SALDO</th>
                        <th class="border-0 text-muted">CATATAN</th>
                        <th class="border-0 text-muted">OLEH</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($produk->stockHistories as $history)
                        <tr>
                            <td>{{ $history->created_at ? $history->created_at->format('d/m/Y, H.i.s') : '-' }}</td>
                            <td>
                                @php
                                    $tipe = strtolower($history->tipe ?? '');
                                @endphp
                                @if($tipe == 'masuk')
                                    <i class="fas fa-arrow-up text-success mr-1"></i> Masuk
                                @elseif($tipe == 'stok awal')
                                    <i class="fas fa-box text-success mr-1"></i> Stok Awal
                                @else
                                    <i class="fas fa-arrow-down text-danger mr-1"></i> Keluar
                                @endif
                            </td>
                            <td
                                class="{{ ($tipe == 'masuk' || $tipe == 'stok awal') ? 'text-success' : 'text-danger' }} font-weight-bold">
                                {{ ($tipe == 'masuk' || $tipe == 'stok awal') ? '+' : '-' }}{{ $history->jumlah }}
                            </td>
                            <td class="font-weight-bold text-primary">
                                {{ $history->stok_akhir }}
                            </td>
                            <td class="text-muted">{{ $history->catatan ?? '-' }}</td>
                            <td class="text-muted">{{ $history->created_by ?? (optional($history->user)->name ?? 'Admin') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-3">Belum ada riwayat stok.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>