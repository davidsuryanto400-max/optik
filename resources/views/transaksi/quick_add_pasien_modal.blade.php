<div class="modal fade" id="modalQuickAddPasien" tabindex="-1" role="dialog" aria-labelledby="modalQuickAddLabel"
    aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title font-weight-bold" id="modalQuickAddLabel">Tambah Pasien Baru</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formQuickAddPasien">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="quick_nama" class="font-weight-bold text-muted text-sm">Nama</label>
                        <input type="text" class="form-control border-light bg-light" id="quick_nama" name="nama"
                            required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label for="quick_no_hp" class="font-weight-bold text-muted text-sm">Telepon</label>
                            <input type="text" class="form-control border-light bg-light" id="quick_no_hp" name="no_hp">
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="quick_tgl_lahir" class="font-weight-bold text-muted text-sm">Tanggal
                                Lahir</label>
                            <input type="date" class="form-control border-light bg-light" id="quick_tgl_lahir"
                                name="tgl_lahir">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="quick_last_exam_date" class="font-weight-bold text-muted text-sm">Tanggal Periksa
                            Terakhir</label>
                        <input type="date" class="form-control border-light bg-light" id="quick_last_exam_date"
                            name="last_exam_date" value="{{ date('Y-m-d') }}">
                    </div>
                    <div class="form-group">
                        <label for="quick_alamat" class="font-weight-bold text-muted text-sm">Alamat</label>
                        <textarea class="form-control border-light bg-light" id="quick_alamat" name="alamat"
                            rows="2"></textarea>
                    </div>

                    <hr>
                    <h6 class="font-weight-bold mb-3">Resep Kacamata (Pemeriksaan Terakhir)</h6>

                    <div class="row">
                        <div class="col-md-3 form-group">
                            <label for="quick_sph_r" class="font-weight-bold text-muted text-xs">SPH Kanan</label>
                            <input type="number" step="0.25" class="form-control border-light bg-light" id="quick_sph_r"
                                name="sph_r" placeholder="0.00">
                        </div>
                        <div class="col-md-3 form-group">
                            <label for="quick_cyl_r" class="font-weight-bold text-muted text-xs">CYL Kanan</label>
                            <input type="number" step="0.25" class="form-control border-light bg-light" id="quick_cyl_r"
                                name="cyl_r" placeholder="0.00">
                        </div>
                        <div class="col-md-3 form-group">
                            <label for="quick_sph_l" class="font-weight-bold text-muted text-xs">SPH Kiri</label>
                            <input type="number" step="0.25" class="form-control border-light bg-light" id="quick_sph_l"
                                name="sph_l" placeholder="0.00">
                        </div>
                        <div class="col-md-3 form-group">
                            <label for="quick_cyl_l" class="font-weight-bold text-muted text-xs">CYL Kiri</label>
                            <input type="number" step="0.25" class="form-control border-light bg-light" id="quick_cyl_l"
                                name="cyl_l" placeholder="0.00">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 form-group">
                            <label for="quick_pd" class="font-weight-bold text-muted text-xs">Pupillary Distance
                                (PD)</label>
                            <input type="text" class="form-control border-light bg-light" id="quick_pd" name="pd"
                                placeholder="62">
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-outline-secondary px-4" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary px-4" id="btnSaveQuickPasien">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>