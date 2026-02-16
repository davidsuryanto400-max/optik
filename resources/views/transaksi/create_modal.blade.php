<div class="modal fade" id="modalCreateTransaksi" tabindex="-1" role="dialog" aria-labelledby="modalCreateLabel"
    aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable" role="document"
        style="max-width: 95vw;">
        <div class="modal-content bg-light">
            <div class="modal-header bg-white border-bottom-0 py-3">
                <h5 class="modal-title font-weight-bold" id="modalCreateLabel">Buat Penjualan Baru</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-0">
                <div class="row no-gutters">

                    <!-- LEFT COLUMN: Patient & Items -->
                    <div class="col-lg-7 p-4">

                        <!-- Patient Section -->
                        <div class="card shadow-sm border-0 mb-4">
                            <!-- ... content ... -->
                            <!-- (Keeping content same, just modifying logic) -->
                            <!-- Actually I need to be careful not to delete content I'm not seeing. -->
                            <!-- I will target specific lines to remove styles -->
                        </div>
                    </div>
                    <!-- Wait, if I replace the whole block I might lose content. -->
                    <!-- The instruction says "Remove fixed height". -->
                    <!-- I'll use a more targeted replace or ensure I include everything. -->

                    <!-- Let's do it in chunks. -->

                    <!-- LEFT COLUMN -->
                    <div class="col-lg-7 p-4">


                        <!-- Patient Section -->
                        <div class="card shadow-sm border-0 mb-4">
                            <div class="card-body p-4">
                                <div class="row">
                                    <div class="col-md-7">
                                        <div class="form-group mb-1">
                                            <label class="text-sm font-weight-bold text-muted">Pilih Pasien</label>
                                            <div class="d-flex">
                                                <select class="form-control select2" id="pasien_id" name="pasien_id"
                                                    style="width: 100%;">
                                                    <option value="">Cari Pasien...</option>
                                                </select>
                                                <button class="btn btn-primary ml-2 btn-add-pasien-quick" type="button"
                                                    title="Pasien Baru">
                                                    <i class="fas fa-user-plus"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="form-group mb-1">
                                            <label class="text-sm font-weight-bold text-muted">Nomor Nota Manual
                                                <small>(Opsional)</small></label>
                                            <input type="text" class="form-control" id="nota_manual"
                                                placeholder="Contoh: INV/2026/01/001">
                                        </div>
                                    </div>
                                </div>

                                <div class="custom-control custom-checkbox mt-3">
                                    <input type="checkbox" class="custom-control-input" id="checkResepBaru">
                                    <label class="custom-control-label font-weight-bold text-dark"
                                        for="checkResepBaru">Buat Resep Baru untuk Pasien ini?</label>
                                </div>

                                <!-- Prescription Form -->
                                <div id="resepForm" class="mt-3 bg-light p-3 rounded border collapse show">
                                    <h6 class="text-xs font-weight-bold text-muted text-uppercase mb-3">Detail Resep
                                        Baru</h6>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group mb-2">
                                                <label class="text-xs text-muted">SPH Kanan</label>
                                                <input type="number" step="0.25" class="form-control form-control-sm"
                                                    id="new_sph_r" placeholder="0.00">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group mb-2">
                                                <label class="text-xs text-muted">CYL Kanan</label>
                                                <input type="number" step="0.25" class="form-control form-control-sm"
                                                    id="new_cyl_r" placeholder="0.00">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group mb-2">
                                                <label class="text-xs text-muted">SPH Kiri</label>
                                                <input type="number" step="0.25" class="form-control form-control-sm"
                                                    id="new_sph_l" placeholder="0.00">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group mb-2">
                                                <label class="text-xs text-muted">CYL Kiri</label>
                                                <input type="number" step="0.25" class="form-control form-control-sm"
                                                    id="new_cyl_l" placeholder="0.00">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group mb-0">
                                                <label class="text-xs text-muted">Pupillary Distance (PD)</label>
                                                <input type="text" class="form-control form-control-sm" id="new_pd"
                                                    placeholder="62">
                                            </div>
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
                                            <div class="d-flex">
                                                <select class="form-control select2-product" id="selectFrame"
                                                    data-tipe="1" style="width: 100%;">
                                                    <option value="">Pilih Frame</option>
                                                </select>
                                            </div>
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
                                            <option value="">Pilih Produk Lain (Cairan, Softlens, dll)</option>
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
                            <!-- Empty State -->
                            <div id="cartEmptyState" class="text-center py-5 text-muted mt-5">
                                <div class="mb-3">
                                    <span class="fa-stack fa-2x">
                                        <i class="fas fa-circle fa-stack-2x text-light"></i>
                                        <i class="fas fa-shopping-basket fa-stack-1x text-secondary"></i>
                                    </span>
                                </div>
                                <p class="small">Belum ada item dipilih</p>
                            </div>
                            <!-- Items will be appended here -->
                        </div>

                        <!-- Summary Section -->
                        <div class="p-3 bg-light border-top shadow-sm" style="z-index: 10;">

                            <!-- Subtotal & Discount -->
                            <div class="row align-items-center mb-2">
                                <div class="col-6">
                                    <span class="text-secondary small font-weight-bold">Subtotal</span>
                                </div>
                                <div class="col-6 text-right">
                                    <span class="font-weight-bold text-dark" id="labelSubtotal">Rp 0</span>
                                </div>
                            </div>

                            <div class="row align-items-center mb-2">
                                <div class="col-5">
                                    <span class="text-secondary small">Diskon</span>
                                </div>
                                <div class="col-7">
                                    <div class="input-group input-group-sm">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-white border-right-0 text-muted">Rp</span>
                                        </div>
                                        <input type="text" class="form-control text-right border-left-0 rupiah-input"
                                            id="inputDiskon" placeholder="0">
                                    </div>
                                </div>
                            </div>

                            <div class="row align-items-center mb-3">
                                <div class="col-6">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="checkPpn">
                                        <label class="custom-control-label small text-secondary" for="checkPpn">PPN
                                            (11%)</label>
                                    </div>
                                </div>
                                <div class="col-6 text-right">
                                    <span class="font-weight-bold text-dark small" id="labelPpn">Rp 0</span>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center pt-2 border-top mb-2">
                                <span class="h6 font-weight-bold text-dark mb-0">Grand Total</span>
                                <span class="h5 font-weight-bold text-primary mb-0" id="labelGrandTotal">Rp 0</span>
                            </div>

                            <!-- Payment Method -->
                            <div class="form-group mb-2">
                                <label class="font-weight-bold text-xs text-muted text-uppercase mb-1">Metode
                                    Pembayaran</label>
                                <div class="btn-group btn-group-toggle w-100 btn-group-sm shadow-sm"
                                    data-toggle="buttons">
                                    <label class="btn btn-outline-white bg-white text-dark border active">
                                        <input type="radio" name="payment_method" value="Tunai" checked> Tunai
                                    </label>
                                    <label class="btn btn-outline-white bg-white text-dark border">
                                        <input type="radio" name="payment_method" value="Debit"> Debit
                                    </label>
                                    <label class="btn btn-outline-white bg-white text-dark border">
                                        <input type="radio" name="payment_method" value="Kartu Kredit"> Kredit
                                    </label>
                                    <label class="btn btn-outline-white bg-white text-dark border">
                                        <input type="radio" name="payment_method" value="BPJS" id="btnRadioBpjs"> BPJS
                                    </label>
                                </div>
                            </div>

                            <!-- BPJS Details -->
                            <div id="bpjsSection"
                                class="bg-white p-3 rounded mb-3 border d-none shadow-sm position-relative overflow-hidden">
                                <div class="position-absolute"
                                    style="top:0; left:0; width:4px; height:100%; background:#28a745;"></div>
                                <label class="text-xs font-weight-bold text-muted mb-2">Kelas BPJS</label>
                                <div class="btn-group btn-group-toggle w-100 btn-group-sm" data-toggle="buttons">
                                    <label class="btn btn-outline-success">
                                        <input type="radio" name="bpjs_kelas" value="1" data-cover="300000"> Kls 1
                                    </label>
                                    <label class="btn btn-outline-success">
                                        <input type="radio" name="bpjs_kelas" value="2" data-cover="200000"> Kls 2
                                    </label>
                                    <label class="btn btn-outline-success">
                                        <input type="radio" name="bpjs_kelas" value="3" data-cover="150000"> Kls 3
                                    </label>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mt-2 border-top pt-2">
                                    <span class="text-xs text-success font-weight-bold">Cover:</span>
                                    <span class="font-weight-bold text-success" id="labelBpjsCover">Rp 0</span>
                                </div>
                            </div>

                            <!-- Net Payment (Customer Bill) -->
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="font-weight-bold text-dark small">Total Tagihan Pasien</span>
                                <span class="h6 font-weight-bold text-danger mb-0" id="labelTotalBayar">Rp 0</span>
                            </div>

                            <!-- Payment Input -->
                            <div class="row align-items-center mb-2">
                                <div class="col-5">
                                    <span class="font-weight-bold text-dark small">Bayar</span>
                                </div>
                                <div class="col-7">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span
                                                class="input-group-text bg-primary text-white border-0 font-weight-bold">Rp</span>
                                        </div>
                                        <input type="text"
                                            class="form-control text-right border-primary font-weight-bold rupiah-input"
                                            id="inputBayar" placeholder="0" style="font-size: 1.2rem;">
                                    </div>
                                </div>
                            </div>

                            <!-- Change/Due -->
                            <div class="p-2 rounded mt-2 d-flex justify-content-between align-items-center"
                                style="background-color: #e9ecef;" id="boxKembalian">
                                <span class="font-weight-bold text-secondary small text-uppercase">Kembalian</span>
                                <span class="h6 font-weight-bold text-secondary mb-0" id="labelKembalian">Rp 0</span>
                            </div>
                        </div>
                    </div>

                    <!-- Button moved to footer -->
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer bg-white border-top">
        <button type="button" class="btn btn-light border font-weight-bold mr-auto" data-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-primary font-weight-bold shadow-sm px-4" id="btnProsesTransaksi" disabled>
            <i class="fas fa-save mr-2"></i> Simpan Penjualan
        </button>
    </div>
</div>
</div>
</div>