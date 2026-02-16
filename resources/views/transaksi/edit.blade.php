@extends('layouts.app')

@section('title', 'Edit Transaksi')

@section('content')
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white font-weight-bold py-3">
                    <h5 class="mb-0">Edit Penjualan - {{ $transaksi->no_transaksi }}</h5>
                </div>
                <div class="card-body p-0">
                    <div class="row no-gutters">

                        <!-- LEFT COLUMN: Patient & Items -->
                        <div class="col-lg-7 p-4">

                            <!-- Patient Section -->
                            <div class="card shadow-sm border-0 mb-4">
                                <div class="card-body p-4">
                                    <div class="row">
                                        <div class="col-md-7">
                                            <div class="form-group mb-1">
                                                <label class="text-sm font-weight-bold text-muted">Pilih Pasien</label>
                                                <select class="form-control select2" id="pasien_id" name="pasien_id"
                                                    style="width: 100%;">
                                                    <option value="{{ $transaksi->pasien_id }}" selected>
                                                        {{ $transaksi->pasien->nama }}
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="form-group mb-1">
                                                <label class="text-sm font-weight-bold text-muted">Nomor Nota Manual
                                                    <small>(Opsional)</small></label>
                                                <input type="text" class="form-control" id="nota_manual"
                                                    value="{{ $transaksi->nota_manual }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Product Selection -->
                            <div class="card shadow-sm border-0">
                                <div class="card-header bg-white font-weight-bold py-3">
                                    Tambah Item ke Keranjang
                                </div>
                                <div class="card-body p-4">
                                    <!-- Paket Kacamata -->
                                    <div class="mb-4">
                                        <label class="font-weight-bold mb-2">1. Paket Kacamata</label>
                                        <div class="row">
                                            <div class="col-md-6 mb-2">
                                                <small class="text-muted d-block mb-1">Frame</small>
                                                <select class="form-control select2-product" id="selectFrame" data-tipe="1"
                                                    style="width: 100%;">
                                                    <option value="">Pilih Frame</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6 mb-2">
                                                <small class="text-muted d-block mb-1">Lensa</small>
                                                <div class="d-flex">
                                                    <select class="form-control select2-product" id="selectLensa"
                                                        data-tipe="2" style="width: 100%;">
                                                        <option value="">Pilih Lensa</option>
                                                    </select>
                                                    <button class="btn btn-light ml-2 border" id="btnAddPaket" type="button"
                                                        title="Tambah Paket">
                                                        <i class="fas fa-plus text-primary"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="border-top my-3"></div>
                                    <!-- Produk Lain -->
                                    <div>
                                        <label class="font-weight-bold mb-2">2. Produk Lain</label>
                                        <div class="d-flex">
                                            <select class="form-control select2-product" id="selectProdukLain"
                                                data-tipe="all" style="width: 100%;">
                                                <option value="">Pilih Produk Lain</option>
                                            </select>
                                            <button class="btn btn-light ml-2 border" id="btnAddProdukLain" type="button"
                                                title="Tambah Produk">
                                                <i class="fas fa-plus text-primary"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- RIGHT COLUMN: Cart & Summary -->
                        <div class="col-lg-5 bg-white border-left">
                            <div class="p-3 bg-light border-bottom">
                                <h6 class="font-weight-bold mb-0 text-uppercase text-secondary text-xs letter-spacing-1">
                                    <i class="fas fa-shopping-cart mr-1"></i> Ringkasan Pesanan
                                </h6>
                            </div>

                            <!-- Cart Items List -->
                            <div class="p-3" id="cartItemsContainer"
                                style="background-color: #ffffff; min-height: 250px; max-height: 400px; overflow-y: auto;">
                                <!-- Items will be populated via JS -->
                            </div>

                            <!-- Summary Section -->
                            <div class="p-4 bg-light border-top shadow-sm">
                                <div class="row align-items-center mb-2">
                                    <div class="col-6"><span class="text-secondary small font-weight-bold">Subtotal</span>
                                    </div>
                                    <div class="col-6 text-right"><span class="font-weight-bold text-dark"
                                            id="labelSubtotal">Rp 0</span></div>
                                </div>
                                <div class="row align-items-center mb-2">
                                    <div class="col-5"><span class="text-secondary small">Diskon</span></div>
                                    <div class="col-7">
                                        <div class="input-group input-group-sm">
                                            <div class="input-group-prepend"><span
                                                    class="input-group-text bg-white border-right-0 text-muted">Rp</span>
                                            </div>
                                            <input type="text" class="form-control text-right border-left-0 rupiah-input"
                                                id="inputDiskon"
                                                value="{{ number_format($transaksi->diskon, 0, ',', '.') }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="row align-items-center mb-3">
                                    <div class="col-6">
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" class="custom-control-input" id="checkPpn" {{ $transaksi->pajak > 0 ? 'checked' : '' }}>
                                            <label class="custom-control-label small text-secondary" for="checkPpn">PPN
                                                (11%)</label>
                                        </div>
                                    </div>
                                    <div class="col-6 text-right"><span class="font-weight-bold text-dark small"
                                            id="labelPpn">Rp 0</span></div>
                                </div>
                                <div class="d-flex justify-content-between align-items-center pt-3 border-top mb-3">
                                    <span class="h6 font-weight-bold text-dark mb-0">Grand Total</span>
                                    <span class="h4 font-weight-bold text-primary mb-0" id="labelGrandTotal">Rp 0</span>
                                </div>

                                <!-- Payment Method -->
                                <div class="form-group mb-3">
                                    <label class="font-weight-bold text-xs text-muted text-uppercase mb-2">Metode
                                        Pembayaran</label>
                                    <div class="btn-group btn-group-toggle w-100 btn-group-sm shadow-sm"
                                        data-toggle="buttons">
                                        <label
                                            class="btn btn-outline-white bg-white text-dark border {{ $transaksi->payment_method == 'Tunai' ? 'active' : '' }}">
                                            <input type="radio" name="payment_method" value="Tunai" {{ $transaksi->payment_method == 'Tunai' ? 'checked' : '' }}> Tunai
                                        </label>
                                        <label
                                            class="btn btn-outline-white bg-white text-dark border {{ $transaksi->payment_method == 'Debit' ? 'active' : '' }}">
                                            <input type="radio" name="payment_method" value="Debit" {{ $transaksi->payment_method == 'Debit' ? 'checked' : '' }}> Debit
                                        </label>
                                        <label
                                            class="btn btn-outline-white bg-white text-dark border {{ $transaksi->payment_method == 'Kartu Kredit' ? 'active' : '' }}">
                                            <input type="radio" name="payment_method" value="Kartu Kredit" {{ $transaksi->payment_method == 'Kartu Kredit' ? 'checked' : '' }}> Kredit
                                        </label>
                                        <label
                                            class="btn btn-outline-white bg-white text-dark border {{ $transaksi->payment_method == 'BPJS' ? 'active' : '' }}">
                                            <input type="radio" name="payment_method" value="BPJS" id="btnRadioBpjs" {{ $transaksi->payment_method == 'BPJS' ? 'checked' : '' }}> BPJS
                                        </label>
                                    </div>
                                </div>

                                <!-- BPJS Logic -->
                                <div id="bpjsSection"
                                    class="bg-white p-3 rounded mb-3 border {{ $transaksi->payment_method == 'BPJS' ? '' : 'd-none' }} shadow-sm position-relative overflow-hidden">
                                    <div class="position-absolute"
                                        style="top:0; left:0; width:4px; height:100%; background:#28a745;"></div>
                                    <label class="text-xs font-weight-bold text-muted mb-2">Kelas BPJS</label>
                                    <div class="btn-group btn-group-toggle w-100 btn-group-sm" data-toggle="buttons">
                                        <label
                                            class="btn btn-outline-success {{ $transaksi->bpjs_cover == 300000 ? 'active' : '' }}">
                                            <input type="radio" name="bpjs_kelas" value="1" data-cover="300000" {{ $transaksi->bpjs_cover == 300000 ? 'checked' : '' }}> Kls 1
                                        </label>
                                        <label
                                            class="btn btn-outline-success {{ $transaksi->bpjs_cover == 200000 ? 'active' : '' }}">
                                            <input type="radio" name="bpjs_kelas" value="2" data-cover="200000" {{ $transaksi->bpjs_cover == 200000 ? 'checked' : '' }}> Kls 2
                                        </label>
                                        <label
                                            class="btn btn-outline-success {{ $transaksi->bpjs_cover == 150000 ? 'active' : '' }}">
                                            <input type="radio" name="bpjs_kelas" value="3" data-cover="150000" {{ $transaksi->bpjs_cover == 150000 ? 'checked' : '' }}> Kls 3
                                        </label>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mt-2 border-top pt-2">
                                        <span class="text-xs text-success font-weight-bold">Cover:</span>
                                        <span class="font-weight-bold text-success" id="labelBpjsCover">Rp
                                            {{ number_format($transaksi->bpjs_cover, 0, ',', '.') }}</span>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="font-weight-bold text-dark small">Total Tagihan Pasien</span>
                                    <span class="h5 font-weight-bold text-danger mb-0" id="labelTotalBayar">Rp 0</span>
                                </div>

                                <div class="row align-items-center mb-2">
                                    <div class="col-5"><span class="font-weight-bold text-dark small">Bayar</span></div>
                                    <div class="col-7">
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span
                                                    class="input-group-text bg-primary text-white border-0 font-weight-bold">Rp</span>
                                            </div>
                                            <input type="text"
                                                class="form-control text-right border-primary font-weight-bold rupiah-input"
                                                id="inputBayar" style="font-size: 1.2rem;"
                                                value="{{ number_format($transaksi->bayar, 0, ',', '.') }}">
                                        </div>
                                    </div>
                                </div>

                                <div class="p-3 rounded mt-3 d-flex justify-content-between align-items-center"
                                    style="background-color: #e9ecef;">
                                    <span class="font-weight-bold text-secondary small text-uppercase">Kembalian</span>
                                    <span class="h5 font-weight-bold text-secondary mb-0" id="labelKembalian">Rp 0</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-white border-top py-3">
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('transaksi.index') }}" class="btn btn-light border font-weight-bold">Batal</a>
                        <button type="button" class="btn btn-primary font-weight-bold shadow-sm px-4"
                            id="btnUpdateTransaksi">
                            <i class="fas fa-save mr-2"></i> Simpan Perubahan
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // --- DATA INITIALIZATION (Top Level) ---
        // Define data immediately to ensure it's available
        let initialItems = [];
        try {
            @php
                $initialItems = $transaksi->items->map(function ($item) {
                    return [
                        'id' => $item->produk_id,
                        'name' => optional($item->produk)->nama ?? 'Produk Terhapus',
                        'price' => (float) $item->harga,
                        'qty' => (int) $item->qty
                    ];
                });
            @endphp
            initialItems = @json($initialItems);
            console.log("Initial Items Loaded (Top Level):", initialItems);
        } catch (e) {
            console.error("Critical Error loading initial data:", e);
            // alert("Error loading data: " + e.message);
        }

        $(document).ready(function () {
            // --- 1. Variables & Helper Functions ---
            let cart = [];

            // --- IMMEDIATE CART POPULATION ---
            // Run this FIRST before any plugins to ensure data is there
            if (initialItems && initialItems.length > 0) {
                // ALERT for debugging - remove after verified
                // alert("Debug: Found " + initialItems.length + " items. Populating cart...");

                initialItems.forEach(item => {
                    try {
                        let id = item.id;
                        let name = item.name;
                        let price = parseFloat(item.price);
                        let qty = parseInt(item.qty);

                        // Push directly to cart array to avoid any function/scope issues
                        cart.push({ id: id, name: name, price: price, qty: qty });
                    } catch (err) {
                        console.error("Error adding item:", item, err);
                    }
                });
            }

            function formatRupiah(angka) {
                var number_string = angka.toString().replace(/[^,\d]/g, '').toString(),
                    split = number_string.split(','),
                    sisa = split[0].length % 3,
                    rupiah = split[0].substr(0, sisa),
                    ribuan = split[0].substr(sisa).match(/\d{3}/gi);
                if (ribuan) {
                    separator = sisa ? '.' : '';
                    rupiah += separator + ribuan.join('.');
                }
                return rupiah;
            }

            function cleanNumber(val) {
                return parseInt(val.toString().replace(/\./g, '')) || 0;
            }

            function calculateTotals() {
                let subtotal = cart.reduce((sum, item) => sum + (item.price * item.qty), 0);
                let diskon = cleanNumber($('#inputDiskon').val());
                let ppnEnabled = $('#checkPpn').is(':checked');
                let afterDiskon = Math.max(0, subtotal - diskon);
                let ppn = ppnEnabled ? (afterDiskon * 0.11) : 0;
                let grandTotal = afterDiskon + ppn;

                let paymentMethod = $('input[name="payment_method"]:checked').val();
                let bpjsCover = 0;
                if (paymentMethod === 'BPJS') {
                    bpjsCover = parseInt($('input[name="bpjs_kelas"]:checked').data('cover')) || 0;
                }

                let totalBayarPasien = Math.max(0, grandTotal - bpjsCover);
                let bayar = cleanNumber($('#inputBayar').val());
                let kembalian = bayar - totalBayarPasien;

                $('#labelSubtotal').text('Rp ' + subtotal.toLocaleString('id-ID'));
                $('#labelPpn').text('Rp ' + ppn.toLocaleString('id-ID'));
                $('#labelGrandTotal').text('Rp ' + grandTotal.toLocaleString('id-ID'));
                $('#labelBpjsCover').text('Rp ' + bpjsCover.toLocaleString('id-ID'));
                $('#labelTotalBayar').text('Rp ' + totalBayarPasien.toLocaleString('id-ID'));

                if (kembalian >= 0) {
                    $('#boxKembalian').removeClass('bg-danger text-white').addClass('bg-light text-secondary');
                    $('#labelKembalian').text('Rp ' + kembalian.toLocaleString('id-ID'));
                } else {
                    $('#boxKembalian').removeClass('bg-light text-secondary').addClass('bg-danger text-white');
                    $('#labelKembalian').text('Kurang: Rp ' + Math.abs(kembalian).toLocaleString('id-ID'));
                }
            }

            function renderCart() {
                let html = '';
                if (cart.length === 0) {
                    $('#cartItemsContainer').html('<div class="text-center py-5 text-muted"><p class="small">Belum ada item dipilih</p></div>');
                    $('#labelSubtotal').text('Rp 0'); // Reset subtotal visual
                    return;
                }
                cart.forEach((item, index) => {
                    html += `
                            <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-2">
                                <div>
                                    <h6 class="mb-0 text-sm font-weight-bold">${item.name}</h6>
                                    <small class="text-muted">Rp ${parseInt(item.price).toLocaleString('id-ID')}</small>
                                </div>
                                <div class="d-flex align-items-center">
                                    <button class="btn btn-xs btn-outline-danger btn-remove-item" data-index="${index}"><i class="fas fa-minus"></i></button>
                                    <span class="mx-2 font-weight-bold">${item.qty}</span>
                                    <button class="btn btn-xs btn-outline-success btn-add-item" data-index="${index}"><i class="fas fa-plus"></i></button>
                                </div>
                            </div>`;
                });
                $('#cartItemsContainer').html(html);
            }

            // Initial Render
            renderCart();
            calculateTotals();

            function addToCart(id, name, price, qty = 1) {
                // Safety casting
                let priceVal = parseFloat(price);
                let qtyVal = parseInt(qty);

                console.log("Adding to Cart:", { id, name, priceVal, qtyVal });

                let existing = cart.find(c => c.id == id);
                if (existing) {
                    existing.qty += qtyVal;
                } else {
                    cart.push({ id: id, name: name, price: priceVal, qty: qtyVal });
                }
                renderCart();
                calculateTotals();
            }

            // --- 2. Plugin Initialization ---
            try {
                // Patient Search
                $('#pasien_id').select2({
                    theme: 'bootstrap4',
                    placeholder: 'Cari Pasien (Nama / No HP)...',
                    ajax: {
                        url: '{{ url("transaksi/get-pasiens") }}',
                        dataType: 'json',
                        delay: 250,
                        data: function (params) { return { q: params.term }; },
                        processResults: function (data) {
                            return {
                                results: $.map(data, function (item) {
                                    return {
                                        text: item.nama + ' (' + (item.no_hp || '-') + ')',
                                        id: item.id,
                                        data: item
                                    }
                                })
                            };
                        },
                        cache: true
                    }
                });

                $('.select2-product').select2({
                    theme: 'bootstrap4',
                    ajax: {
                        url: '{{ url("transaksi/get-products") }}',
                        dataType: 'json',
                        delay: 250,
                        data: function (params) { return { q: params.term, type: $(this).data('tipe') }; },
                        processResults: function (data) {
                            return {
                                results: data.map(function (item) {
                                    return { id: item.id, text: item.nama + ' (Stok: ' + item.stok + ')', price: item.harga_jual, name: item.nama };
                                })
                            };
                        },
                        cache: true
                    }
                });
            } catch (e) {
                console.error("Plugin Init Failed:", e);
            }

            // --- 3. Event Listeners ---
            $('#btnAddPaket').click(function () {
                let frame = $('#selectFrame').select2('data')[0];
                let lensa = $('#selectLensa').select2('data')[0];
                
                // Debugging
                console.log("Frame Selection:", frame);
                console.log("Lensa Selection:", lensa);

                // Check if valid item (must have id and not be the placeholder)
                if (frame && frame.id && lensa && lensa.id) {
                    addToCart(frame.id, frame.name, frame.price);
                    addToCart(lensa.id, lensa.name, lensa.price);
                    $('#selectFrame').val(null).trigger('change');
                    $('#selectLensa').val(null).trigger('change');
                } else {
                    Swal.fire('Info', 'Pilih Frame dan Lensa terlebih dahulu!', 'info');
                }
            });

            $('#btnAddProdukLain').click(function () {
                let prod = $('#selectProdukLain').select2('data')[0];
                console.log("Produk Lain Selection:", prod);

                if (prod && prod.id) {
                    addToCart(prod.id, prod.name, prod.price);
                    $('#selectProdukLain').val(null).trigger('change');
                } else {
                    Swal.fire('Info', 'Pilih Produk terlebih dahulu!', 'info');
                }
            });

            $(document).on('click', '.btn-remove-item', function () {
                let idx = $(this).data('index');
                if (cart[idx].qty > 1) { cart[idx].qty--; } else { cart.splice(idx, 1); }
                renderCart();
                calculateTotals();
            });

            $(document).on('click', '.btn-add-item', function () {
                let idx = $(this).data('index');
                cart[idx].qty++;
                renderCart();
                calculateTotals();
            });

            $('.rupiah-input').on('keyup', function () {
                $(this).val(formatRupiah(this.value));
                calculateTotals();
            });

            $('#checkPpn, input[name="payment_method"], input[name="bpjs_kelas"]').change(function () {
                if ($(this).attr('name') === 'payment_method') {
                    if ($(this).val() === 'BPJS') {
                        $('#bpjsSection').removeClass('d-none');
                    } else {
                        $('#bpjsSection').addClass('d-none');
                    }
                }
                calculateTotals();
            });

            $('#btnUpdateTransaksi').click(function () {
                if (cart.length === 0) {
                    Swal.fire('Error', 'Keranjang masih kosong', 'error');
                    return;
                }

                let payload = {
                    _token: '{{ csrf_token() }}',
                    _method: 'PUT',
                    pasien_id: $('#pasien_id').val(),
                    nota_manual: $('#nota_manual').val(),
                    items: cart,
                    subtotal: cleanNumber($('#labelSubtotal').text().replace('Rp ', '')),
                    diskon: cleanNumber($('#inputDiskon').val()),
                    ppn: cleanNumber($('#labelPpn').text().replace('Rp ', '')),
                    grand_total: cleanNumber($('#labelGrandTotal').text().replace('Rp ', '')),
                    payment_method: $('input[name="payment_method"]:checked').val(),
                    bpjs_cover: cleanNumber($('#labelBpjsCover').text().replace('Rp ', '')),
                    bayar: cleanNumber($('#inputBayar').val()),
                    kembalian: cleanNumber($('#labelKembalian').text().replace('Rp ', '').replace('Kurang: Rp ', '-'))
                };

                $.ajax({
                    url: '{{ route("transaksi.update", $transaksi->id) }}',
                    type: 'POST',
                    data: payload,
                    success: function (res) {
                        Swal.fire('Sukses', 'Transaksi berhasil diperbarui', 'success').then(() => {
                            window.location.href = '{{ route("transaksi.index") }}';
                        });
                    },
                    error: function (xhr) {
                        Swal.fire('Error', 'Gagal memperbarui transaksi', 'error');
                    }
                });
            });

            // Trigger BPJS UI update on load
            $('input[name="payment_method"]:checked').trigger('change');

        });
    </script>
@endpush