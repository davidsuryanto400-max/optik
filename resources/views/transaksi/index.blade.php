@extends('layouts.app')

@section('title', 'Manajemen Penjualan')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 font-weight-bold">Manajemen Penjualan</h5>
                        <div class="card-tools d-flex align-items-center">
                            <!-- Date Range Picker Placeholder -->
                            <form action="{{ route('transaksi.index') }}" method="GET" class="d-flex align-items-center mr-2" id="filterForm">
                                <div class="input-group input-group-sm mr-1">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-white"><i class="far fa-calendar-alt"></i></span>
                                    </div>
                                    <input type="date" name="start_date" class="form-control" value="{{ $startDate }}" title="Dari Tanggal" onchange="this.form.submit()">
                                </div>
                                <span class="mx-1 text-muted">-</span>
                                <div class="input-group input-group-sm mr-1">
                                    <input type="date" name="end_date" class="form-control" value="{{ $endDate }}" title="Sampai Tanggal" onchange="this.form.submit()">
                                </div>
                                <div class="custom-control custom-checkbox ml-2 mr-3 py-1">
                                    <input type="checkbox" class="custom-control-input" id="lihat_void" name="lihat_void" value="1" {{ $showVoid ? 'checked' : '' }} onchange="this.form.submit()">
                                    <label class="custom-control-label text-sm text-dark font-weight-bold" for="lihat_void">Data Void</label>
                                </div>
                                @if(request('start_date') && request('start_date') != date('Y-m-d') || request('end_date') && request('end_date') != date('Y-m-d') || $showVoid)
                                    <a href="{{ route('transaksi.index') }}" class="btn btn-light btn-sm ml-1 text-danger" title="Reset Filter">
                                        <i class="fas fa-times"></i>
                                    </a>
                                @endif
                            </form>

                            <button type="button" class="btn btn-primary btn-sm" id="btnOpenCreateModal">
                                <i class="fas fa-plus mr-1"></i> Buat Penjualan Baru
                            </button>
                        </div>
                    </div>
                </div>

                <div class="card-body bg-light p-3">
                    <div class="card border-0 shadow-none mb-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="border-0 text-muted text-xs font-weight-bolder text-uppercase pl-4">ID /
                                            Nota Manual</th>
                                        <th class="border-0 text-muted text-xs font-weight-bolder text-uppercase">Nama
                                            Pasien / Tanggal</th>
                                        <th class="border-0 text-muted text-xs font-weight-bolder text-uppercase"
                                            width="35%">Ringkasan Pembelian</th>
                                        <th class="border-0 text-muted text-xs font-weight-bolder text-uppercase">Metode
                                            Bayar</th>
                                        <th class="border-0 text-muted text-xs font-weight-bolder text-uppercase"
                                            width="20%">Rincian Total</th>
                                        <th
                                            class="border-0 text-muted text-xs font-weight-bolder text-uppercase text-right pr-4">
                                            Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($transaksis as $trx)
                                        <tr class="bg-white border-bottom {{ $trx->status == 'void' ? 'opacity-50 grayscale' : '' }}">
                                            <td class="align-top pl-4 py-3">
                                                <div class="font-weight-bold text-dark">{{ $trx->no_transaksi }}</div>
                                                <div class="text-xs text-muted font-italic">
                                                    {{ $trx->nota_manual ?? 'Tanpa Nota Manual' }}</div>
                                            </td>
                                            <td class="align-top py-3">
                                                <div class="d-flex align-items-center">
                                                    <div class="font-weight-bold text-dark mr-2">{{ $trx->pasien->nama }}</div>
                                                    @if($trx->status == 'void')
                                                        <span class="badge badge-danger text-xs px-2 py-1">VOID</span>
                                                    @endif
                                                </div>
                                                <div class="text-xs text-muted">{{ $trx->created_at->format('d/m/Y') }}</div>
                                            </td>
                                            <td class="align-top py-3">
                                                @foreach($trx->items as $item)
                                                    <div class="d-flex justify-content-between text-sm mb-1">
                                                        <span class="text-dark">{{ $item->produk->nama }}</span>
                                                        <span class="text-muted">x {{ $item->qty }}</span>
                                                    </div>
                                                @endforeach
                                            </td>
                                            <td class="align-top py-3">
                                                <span class="text-dark text-sm">{{ $trx->payment_method }}</span>
                                            </td>
                                            <td class="align-top py-3">
                                                <div class="font-weight-bold text-dark">Rp
                                                    {{ number_format($trx->subtotal, 0, ',', '.') }}</div>
                                                @if($trx->diskon > 0)
                                                    <div class="text-xs text-success">Diskon: Rp
                                                        {{ number_format($trx->diskon, 0, ',', '.') }}</div>
                                                @endif
                                                @if($trx->pajak > 0)
                                                    <div class="text-xs text-secondary">PPN: Rp
                                                        {{ number_format($trx->pajak, 0, ',', '.') }}</div>
                                                @endif
                                                <div class="font-weight-bold text-dark text-xs border-top pt-1 mt-1">Total: Rp {{ number_format($trx->total_harga, 0, ',', '.') }}</div>
                                                
                                                @if($trx->bpjs_cover > 0)
                                                    <div class="text-xs text-success font-weight-bold">Cover BPJS: Rp
                                                        {{ number_format($trx->bpjs_cover, 0, ',', '.') }}</div>
                                                @endif

                                                <div class="text-xs text-muted">Bayar: Rp {{ number_format($trx->bayar, 0, ',', '.') }}</div>

                                                @if($trx->kembalian > 0)
                                                    <div class="text-xs text-primary">Kembalian: Rp
                                                        {{ number_format($trx->kembalian, 0, ',', '.') }}</div>
                                                @endif
                                                @if($trx->kembalian <= 0 && ($trx->total_harga - $trx->bpjs_cover) > $trx->bayar)
                                                    <div class="text-xs text-danger font-weight-bold">Kurang: Rp
                                                        {{ number_format(($trx->total_harga - $trx->bpjs_cover) - $trx->bayar, 0, ',', '.') }}</div>
                                                @endif
                                            </td>
                                            <td class="align-top text-right pr-4 py-3">
                                                <a href="{{ route('transaksi.print', $trx->id) }}" target="_blank" class="btn btn-sm btn-outline-primary" title="Print Nota">
                                                    <i class="fas fa-print"></i>
                                                </a>
                                                @if($trx->status !== 'void')
                                                    <button type="button" class="btn btn-link text-primary btn-sm p-0 mr-2 btn-edit-transaksi" data-id="{{ $trx->id }}"
                                                        title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-link text-danger btn-sm p-0 btn-void-transaksi" data-id="{{ $trx->id }}"
                                                        title="Void Transaksi">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-5">
                                                <div class="text-muted">
                                                    <i class="fas fa-shopping-cart fa-3x mb-3"></i>
                                                    <p class="mb-0">Belum ada data penjualan.</p>
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
                    {{ $transaksis->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <!-- Content -->
    </div>
    
    @include('transaksi.create_modal')
    @include('transaksi.quick_add_pasien_modal')
@endsection
@push('styles')
    <style>
        .table thead th {
            border-bottom: 0;
            background-color: #f8f9fa;
        }
        /* Select2 Bootstrap 4 Theme Fixes */
        .select2-container--bootstrap4 .select2-selection--single {
            height: calc(1.5em + 0.75rem + 2px) !important;
        }
        .select2-container--bootstrap4 .select2-selection--single .select2-selection__placeholder {
            line-height: calc(1.5em + 0.75rem) !important;
        }
        .opacity-50 {
            opacity: 0.5;
        }
        .grayscale {
            filter: grayscale(1);
        }
    </style>
@endpush

@push('scripts')
<script>
    $(document).ready(function() {
        // --- Quick Add Patient Logic ---
        
        // Open Modal
        $(document).on('click', '.btn-add-pasien-quick', function() {
            // Reset form
            $('#formQuickAddPasien')[0].reset();
            // Set default date
            $('#quick_last_exam_date').val(new Date().toISOString().split('T')[0]);
            
            $('#modalQuickAddPasien').modal('show');
        });

        // Submit Form
        $('#btnSaveQuickPasien').click(function(e) {
            e.preventDefault();
            
            var btn = $(this);
            var form = $('#formQuickAddPasien');
            
            // Basic Validation
            if(!$('#quick_nama').val()){
                alert('Nama Wajib diisi!');
                return;
            }

            btn.prop('disabled', true).text('Menyimpan...');

            $.ajax({
                url: '{{ route("pasien.store") }}',
                method: 'POST',
                data: form.serialize() + '&_token={{ csrf_token() }}',
                dataType: 'json',
                success: function(response) {
                    if(response.success) {
                        $('#modalQuickAddPasien').modal('hide');
                        
                        // Add to Select2 if not already there
                        if ($('#pasien_id').find("option[value='" + response.data.id + "']").length === 0) {
                            var newOption = new Option(
                                response.data.nama + ' (' + (response.data.no_hp || '-') + ')', 
                                response.data.id, 
                                true, 
                                true
                            );
                            $('#pasien_id').append(newOption);
                        }
                        
                        $('#pasien_id').val(response.data.id).trigger('change');
                        
                        // Manually trigger Select2 selection event with full data object
                        var select2Data = {
                            id: response.data.id,
                            text: response.data.nama + ' (' + (response.data.no_hp || '-') + ')',
                            data: response.data
                        };
                        
                        $('#pasien_id').trigger({
                            type: 'select2:select',
                            params: {
                                data: select2Data
                            }
                        });


                        Swal.fire({
                             toast: true,
                             position: 'top-end',
                             icon: 'success',
                             title: 'Pasien berhasil ditambahkan',
                             showConfirmButton: false,
                             timer: 3000
                        });
                    }
                },
                error: function(xhr) {
                    alert('Gagal: ' + (xhr.responseJSON.message || 'Error occurred'));
                },
                complete: function() {
                    btn.prop('disabled', false).text('Simpan');
                }
            });
        });


        // --- Debug Manual Trigger ---
        $('#btnOpenCreateModal').click(function() {
            var modal = $('#modalCreateTransaksi');
            if(modal.length) {
                modal.modal('show');
            } else {
                alert('Error: Modal element not found!');
            }
        });

        // --- Variables ---
        var cart = [];
        var subtotal = 0;
        var diskon = 0;
        var ppn = 0;
        var grandTotal = 0;
        var bpjsCover = 0; // New Variable
        var totalBayar = 0;
        var kembalian = 0;

        // Initialize Prescription Inputs as Readonly
        $('#resepForm input').prop('readonly', true).css('background-color', '#e9ecef');

        // --- Select2 Initialization ---
        // Patient Search
        $('#pasien_id').select2({
            theme: 'bootstrap4',
            placeholder: 'Cari Pasien (Nama / No HP)...',
            ajax: {
                url: '{{ url("transaksi/get-pasiens") }}',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return { q: params.term };
                },
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

        // Product Search (Generic)
        function initProductSelect(selector, type) {
            $(selector).select2({
                theme: 'bootstrap4',
                placeholder: 'Cari Produk...',
                ajax: {
                    url: '{{ url("transaksi/get-products") }}', 
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            q: params.term,
                            type: type
                        };
                    },
                    processResults: function (data) {
                        return {
                            results: $.map(data, function (item) {
                                return {
                                    text: item.nama + ' (Stok: ' + item.stok + ') - Rp ' + parseInt(item.harga_jual).toLocaleString('id-ID'),
                                    id: item.id,
                                    data: item
                                }
                            })
                        };
                    },
                    cache: true
                }
            });
        }

        initProductSelect('#selectFrame', '1'); // Frame
        initProductSelect('#selectLensa', '2'); // Lensa
        initProductSelect('#selectProdukLain', 'all'); // Other

        // --- Event Handlers ---
        
        // Patient Selection - Auto Fill Prescription
        $('#pasien_id').on('select2:select', function (e) {
            var selection = e.params.data;
            var data = selection.data || selection; // Handle both nested and flat structures
            
            if (data && !$('#checkResepBaru').is(':checked')) {
                $('#new_sph_r').val(data.sph_r || '');
                $('#new_cyl_r').val(data.cyl_r || '');
                $('#new_sph_l').val(data.sph_l || '');
                $('#new_cyl_l').val(data.cyl_l || '');
                $('#new_pd').val(data.pd || '');
            }
        });

        // New Prescription Toggle
        $('#checkResepBaru').change(function() {
            var inputs = $('#resepForm input');
            if($(this).is(':checked')) {
                inputs.val('').prop('readonly', false).css('background-color', '#ffffff');
                $('#resepForm').collapse('show');
            } else {
                 inputs.prop('readonly', true).css('background-color', '#e9ecef');
                 // Try to re-fill if patient selected
                 var data = $('#pasien_id').select2('data')[0]?.data;
                 if (data) {
                    $('#new_sph_r').val(data.sph_r);
                    $('#new_cyl_r').val(data.cyl_r);
                    $('#new_sph_l').val(data.sph_l);
                    $('#new_cyl_l').val(data.cyl_l);
                    $('#new_pd').val(data.pd);
                 } else {
                     inputs.val(''); // Clear if no patient
                 }
            }
        });

        // Payment Method Toggle
        $('input[name="payment_method"]').change(function() {
            var method = $(this).val();
            if (method === 'BPJS') {
                $('#bpjsSection').removeClass('d-none');
            } else {
                $('#bpjsSection').addClass('d-none');
                $('input[name="bpjs_kelas"]').prop('checked', false).parent().removeClass('active');
                bpjsCover = 0;
                $('#labelBpjsCover').text('Rp 0');
                calculateTotals();
            }
        });

        // BPJS Class Selection
        $('input[name="bpjs_kelas"]').change(function() {
            var cover = $(this).data('cover');
            bpjsCover = parseInt(cover) || 0;
            $('#labelBpjsCover').text('Rp ' + bpjsCover.toLocaleString('id-ID'));
            calculateTotals();
        });

        // Add to Cart
        function addToCart(product, qty = 1) {
            // Check if exists
            var existing = cart.find(x => x.id == product.id);
            if(existing) {
                existing.qty += qty;
            } else {
                cart.push({
                    id: product.id,
                    name: product.nama,
                    price: parseFloat(product.harga_jual),
                    qty: qty
                });
            }
            renderCart();
        }

        $('#btnAddPaket').click(function() {
            var frameData = $('#selectFrame').select2('data')[0];
            var lensaData = $('#selectLensa').select2('data')[0];

            if(frameData) addToCart(frameData.data);
            if(lensaData) addToCart(lensaData.data);

            // Reset Selects
            $('#selectFrame').val(null).trigger('change');
            $('#selectLensa').val(null).trigger('change');
        });

        $('#btnAddProdukLain').click(function() {
            var prodData = $('#selectProdukLain').select2('data')[0];
            if(prodData) {
                addToCart(prodData.data);
                $('#selectProdukLain').val(null).trigger('change');
            }
        });

        // Remove / Update Cart (Event delegation)
        $(document).on('click', '.btn-remove-item', function() {
            var id = $(this).data('id');
            cart = cart.filter(x => x.id != id);
            renderCart();
        });

        // Helper: Format Rupiah on Type
        function formatRupiahTyping(angka, prefix){
            var number_string = angka.replace(/[^,\d]/g, '').toString(),
            split   		= number_string.split(','),
            sisa     		= split[0].length % 3,
            rupiah     		= split[0].substr(0, sisa),
            ribuan     		= split[0].substr(sisa).match(/\d{3}/gi);
    
            if(ribuan){
                separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }
    
            rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
            return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
        }

        function cleanNumber(val) {
             if(!val) return 0;
             return parseFloat(val.toString().replace(/\./g, '').replace(/,/g, '.')) || 0;
        }

        // Apply formatting logic
        $(document).on('keyup', '.rupiah-input', function(){
            $(this).val(formatRupiahTyping($(this).val()));
            calculateTotals(); 
        });

        // Calculations
        function renderCart() {
             var html = '';
             subtotal = 0;
             
             if(cart.length === 0) {
                 $('#cartItemsContainer').html($('#cartEmptyState').prop('outerHTML')); 
                 $('#cartEmptyState').show();
             } else {
                 $('#cartEmptyState').hide(); 
                 
                 cart.forEach(item => {
                     var itemTotal = item.price * item.qty;
                     subtotal += itemTotal;
                     
                     html += `
                         <div class="d-flex justify-content-between align-items-center mb-2 border-bottom pb-1">
                             <div>
                                 <div class="font-weight-bold text-dark text-sm">${item.name}</div>
                                 <div class="text-xs text-muted">${item.qty} x Rp ${item.price.toLocaleString('id-ID')}</div>
                             </div>
                             <div class="d-flex align-items-center">
                                 <span class="font-weight-bold text-sm mr-3">Rp ${itemTotal.toLocaleString('id-ID')}</span>
                                 <button class="btn btn-link text-danger btn-sm p-0 btn-remove-item" data-id="${item.id}">
                                     <i class="fas fa-trash"></i>
                                 </button>
                             </div>
                         </div>
                     `;
                 });
                 $('#cartItemsContainer').html(html);
            }
 
            calculateTotals();
        }

        function calculateTotals() {
            diskon = cleanNumber($('#inputDiskon').val());
            var ppnEnabled = $('#checkPpn').is(':checked');
            
            var afterDiskon = Math.max(0, subtotal - diskon);
            ppn = ppnEnabled ? (afterDiskon * 0.11) : 0;
            grandTotal = afterDiskon + ppn;

            // Grand Total = Total Tagihan (before Payment)
            // But if BPJS is involved, Patient pays less.
            
            var totalBayarPasien = Math.max(0, grandTotal - bpjsCover);

            var bayar = cleanNumber($('#inputBayar').val());
            kembalian = bayar - totalBayarPasien;

            // Render Labels
            $('#labelSubtotal').text('Rp ' + subtotal.toLocaleString('id-ID'));
            $('#labelPpn').text('Rp ' + ppn.toLocaleString('id-ID'));
            $('#labelGrandTotal').text('Rp ' + grandTotal.toLocaleString('id-ID'));
            $('#labelTotalBayar').text('Rp ' + totalBayarPasien.toLocaleString('id-ID'));
            // Note: If you want to show "Netto" somewhere, you can. 
            // For now sticking to update logic.
            
            if (kembalian >= 0) {
                 $('#boxKembalian').find('span:first').text('KEMBALIAN');
                 $('#labelKembalian').removeClass('text-danger').addClass('text-success').text('Rp ' + kembalian.toLocaleString('id-ID'));
                 $('#boxKembalian').css('background-color', '#d4edda'); 
                 $('#boxKembalian span').removeClass('text-secondary').addClass('text-success');
            } else {
                 $('#boxKembalian').find('span:first').text('KEKURANGAN');
                 $('#labelKembalian').removeClass('text-success').addClass('text-danger').text('Rp ' + Math.abs(kembalian).toLocaleString('id-ID'));
                 $('#boxKembalian').css('background-color', '#f8d7da');
                 $('#boxKembalian span').removeClass('text-success').addClass('text-danger');
            }

            // Enable/Disable Button
            $('#btnProsesTransaksi').prop('disabled', cart.length === 0 || !$('#pasien_id').val());
        }

        $('#checkPpn').on('change', calculateTotals);
        // input listener removed here because keyup handled above

        // Submit Transaction
        $('#btnProsesTransaksi').click(function() {
            var payload = {
                pasien_id: $('#pasien_id').val(),
                nota_manual: $('#nota_manual').val(),
                new_prescription: $('#checkResepBaru').is(':checked'),
                sph_r: $('#new_sph_r').val(),
                cyl_r: $('#new_cyl_r').val(),
                sph_l: $('#new_sph_l').val(),
                cyl_l: $('#new_cyl_l').val(),
                pd: $('#new_pd').val(),
                items: cart,
                subtotal: subtotal,
                diskon: diskon,
                ppn: ppn,
                grand_total: grandTotal,
                bpjs_cover: bpjsCover, 
                payment_method: $('input[name="payment_method"]:checked').val(),
                bayar: cleanNumber($('#inputBayar').val()), 
                kembalian: kembalian,
                _token: '{{ csrf_token() }}'
            };

            $.ajax({
                url: '{{ route("transaksi.store") }}',
                method: 'POST',
                data: payload,
                success: function(response) {
                    alert('Transaksi berhasil disimpan!');
                    location.reload();
                },
                error: function(xhr) {
                    alert('Gagal: ' + (xhr.responseJSON.message || 'Error occurred'));
                }
            });
        });
        
        // Edit Transaction Password Check
        $(document).on('click', '.btn-edit-transaksi', function() {
            let id = $(this).data('id');
            Swal.fire({
                title: 'Masukkan Password',
                input: 'password',
                inputAttributes: {
                    autocapitalize: 'off'
                },
                showCancelButton: true,
                confirmButtonText: 'Verifikasi',
                showLoaderOnConfirm: true,
                preConfirm: (password) => {
                    return fetch('{{ url("transaksi/check-password") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ password: password })
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(response.statusText)
                        }
                        return response.json()
                    })
                    .catch(error => {
                        Swal.showValidationMessage(
                            `Password salah!`
                        )
                    })
                },
                allowOutsideClick: () => !Swal.isLoading()
            }).then((result) => {
                if (result.isConfirmed && result.value.valid) {
                    window.location.href = '{{ url("transaksi") }}/' + id + '/edit';
                }
            })
        });

        // Void Transaction Password Check
        $(document).on('click', '.btn-void-transaksi', function() {
            let id = $(this).data('id');
            Swal.fire({
                title: 'Konfirmasi Void',
                text: 'Masukkan password admin untuk memvoid transaksi ini. Stok produk akan dikembalikan.',
                input: 'password',
                inputAttributes: {
                    autocapitalize: 'off'
                },
                showCancelButton: true,
                confirmButtonText: 'Void Transaksi',
                confirmButtonColor: '#d33',
                showLoaderOnConfirm: true,
                preConfirm: (password) => {
                    return fetch('{{ url("transaksi/check-password") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ password: password })
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Password salah!')
                        }
                        return response.json()
                    })
                    .catch(error => {
                        Swal.showValidationMessage(
                            `Gagal: ${error.message}`
                        )
                    })
                },
                allowOutsideClick: () => !Swal.isLoading()
            }).then((result) => {
                if (result.isConfirmed && result.value.valid) {
                    // Password correct, proceed to delete
                    $.ajax({
                        url: '{{ url("transaksi") }}/' + id,
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            _method: 'DELETE'
                        },
                        success: function(response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: 'Transaksi telah divoid dan stok dikembalikan.',
                            }).then(() => {
                                location.reload();
                            });
                        },
                        error: function(xhr) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: 'Gagal memvoid transaksi: ' + (xhr.responseJSON?.message || 'Error occurred')
                            });
                        }
                    });
                }
            })
        });
    });
</script>
@endpush