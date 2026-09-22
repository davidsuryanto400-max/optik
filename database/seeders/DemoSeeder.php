<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pasien;
use App\Models\Produk;
use App\Models\Kategori;
use App\Models\Tipe;
use App\Models\Gudang;
use App\Models\Transaksi;
use App\Models\TransaksiItem;
use App\Models\RiwayatPemeriksaan;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DemoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // Bersihkan data lama jika ingin benar-benar demo bersih, tapi lebih baik tidak truncate total agar user lama (admin) tidak hilang.
        // Kita hanya bersihkan data operasional.
        TransaksiItem::truncate();
        RiwayatPemeriksaan::truncate();
        Transaksi::truncate();
        Pasien::truncate();
        \App\Models\StockHistory::truncate();
        
        // Kita biarkan Produk dan Tipe/Kategori/Gudang jika sudah ada, atau buat yang baru
        if(Kategori::count() == 0) Kategori::create(['nama' => 'Umum']);
        if(Gudang::count() == 0) Gudang::create(['nama' => 'Gudang Utama']);
        
        $tipeFrame = Tipe::firstOrCreate(['nama' => 'Frame']);
        $tipeLensa = Tipe::firstOrCreate(['nama' => 'Lensa']);
        $tipeAksesoris = Tipe::firstOrCreate(['nama' => 'Aksesoris']);
        
        $kategoriId = Kategori::first()->id;
        $gudangId = Gudang::first()->id;

        // Bikin Produk
        $produks = [
            // Frames
            ['kode' => 'FRM-001', 'nama' => 'Ray-Ban Aviator Classic', 'tipe_id' => $tipeFrame->id, 'stok' => 15, 'stok_minimum' => 5, 'harga_beli' => 1200000, 'harga_jual' => 1800000],
            ['kode' => 'FRM-002', 'nama' => 'Oakley Holbrook', 'tipe_id' => $tipeFrame->id, 'stok' => 8, 'stok_minimum' => 10, 'harga_beli' => 1500000, 'harga_jual' => 2200000], // Stok rendah
            ['kode' => 'FRM-003', 'nama' => 'Gucci GG0036S', 'tipe_id' => $tipeFrame->id, 'stok' => 0, 'stok_minimum' => 2, 'harga_beli' => 2500000, 'harga_jual' => 3800000], // Stok habis
            ['kode' => 'FRM-004', 'nama' => 'Bonia Elegant', 'tipe_id' => $tipeFrame->id, 'stok' => 20, 'stok_minimum' => 5, 'harga_beli' => 800000, 'harga_jual' => 1300000],
            // Lensas
            ['kode' => 'LNS-001', 'nama' => 'Essilor Crizal Sapphire HR', 'tipe_id' => $tipeLensa->id, 'stok' => 50, 'stok_minimum' => 10, 'harga_beli' => 400000, 'harga_jual' => 850000],
            ['kode' => 'LNS-002', 'nama' => 'Hoya BlueControl', 'tipe_id' => $tipeLensa->id, 'stok' => 4, 'stok_minimum' => 5, 'harga_beli' => 300000, 'harga_jual' => 600000], // Stok rendah
            ['kode' => 'LNS-003', 'nama' => 'Zeiss DriveSafe', 'tipe_id' => $tipeLensa->id, 'stok' => 0, 'stok_minimum' => 5, 'harga_beli' => 600000, 'harga_jual' => 1200000], // Stok habis
            ['kode' => 'LNS-004', 'nama' => 'Lensa Progresif Standar', 'tipe_id' => $tipeLensa->id, 'stok' => 30, 'stok_minimum' => 5, 'harga_beli' => 200000, 'harga_jual' => 450000],
        ];

        DB::table('produks')->truncate(); // Hapus yang ada agar demo clean
        foreach ($produks as $p) {
            $p['kategori_id'] = $kategoriId;
            $p['gudang_id'] = $gudangId;
            Produk::create($p);
        }

        // Bikin Pasien
        $today = Carbon::now();
        $pasiens = [
            ['nama' => 'Budi Santoso', 'no_hp' => '081234567890', 'alamat' => 'Jl. Merdeka No. 1, Jakarta', 'tgl_lahir' => $today->copy()->subYears(35)->subDays(10)->format('Y-m-d')], // Biasa
            ['nama' => 'Siti Aminah', 'no_hp' => '082345678901', 'alamat' => 'Jl. Sudirman No. 2, Bandung', 'tgl_lahir' => $today->copy()->subYears(28)->addDays(2)->format('Y-m-d')], // Ultah 2 hari lagi
            ['nama' => 'Andi Wijaya', 'no_hp' => '083456789012', 'alamat' => 'Jl. Thamrin No. 3, Surabaya', 'tgl_lahir' => $today->copy()->subYears(45)->format('Y-m-d')], // Ultah hari ini
            ['nama' => 'Dewi Lestari', 'no_hp' => '084567890123', 'alamat' => 'Jl. Gatot Subroto No. 4, Medan', 'tgl_lahir' => $today->copy()->subYears(31)->addDays(15)->format('Y-m-d')], // Ultah 15 hari lagi
            ['nama' => 'Rudi Hermawan', 'no_hp' => '085678901234', 'alamat' => 'Jl. Asia Afrika No. 5, Bali', 'tgl_lahir' => $today->copy()->subYears(50)->subDays(30)->format('Y-m-d')],
        ];

        foreach ($pasiens as $p) {
            Pasien::create($p);
        }

        // Bikin Transaksi dan Rekam Medis (Riwayat)
        $pasiensData = Pasien::all();
        $produkFrames = Produk::where('tipe_id', $tipeFrame->id)->get();
        $produkLensas = Produk::where('tipe_id', $tipeLensa->id)->get();

        $transaksiData = [
            [
                'pasien' => $pasiensData[0], // Budi
                'date' => $today->copy()->subDays(5),
                'frame' => $produkFrames[0], // Ray-ban
                'lensa' => $produkLensas[0], // Essilor
                'sph_r' => -1.00, 'cyl_r' => -0.50, 'ax_r' => '90', 'add_r' => '+1.00',
                'sph_l' => -1.50, 'cyl_l' => -0.25, 'ax_l' => '180', 'add_l' => '+1.00',
                'pd' => '62'
            ],
            [
                'pasien' => $pasiensData[1], // Siti
                'date' => $today->copy()->subDays(10),
                'frame' => $produkFrames[1], // Oakley
                'lensa' => $produkLensas[1], // Hoya
                'sph_r' => -2.00, 'cyl_r' => 0, 'ax_r' => '0', 'add_r' => '',
                'sph_l' => -2.50, 'cyl_l' => 0, 'ax_l' => '0', 'add_l' => '',
                'pd' => '60'
            ],
            [
                'pasien' => $pasiensData[2], // Andi
                'date' => $today->copy()->subDays(2),
                'frame' => $produkFrames[3], // Bonia
                'lensa' => $produkLensas[3], // Progresif
                'sph_r' => +1.00, 'cyl_r' => -1.00, 'ax_r' => '45', 'add_r' => '+2.00',
                'sph_l' => +1.25, 'cyl_l' => -0.75, 'ax_l' => '135', 'add_l' => '+2.00',
                'pd' => '64'
            ],
            [
                'pasien' => $pasiensData[0], // Budi beli kacamata lagi (Riwayat 2)
                'date' => $today->copy()->subDays(1),
                'frame' => $produkFrames[1], // Oakley
                'lensa' => $produkLensas[3], // Progresif
                'sph_r' => -1.25, 'cyl_r' => -0.50, 'ax_r' => '90', 'add_r' => '+1.25',
                'sph_l' => -1.75, 'cyl_l' => -0.25, 'ax_l' => '180', 'add_l' => '+1.25',
                'pd' => '62'
            ]
        ];

        foreach ($transaksiData as $idx => $t) {
            // Update latest Pasien info
            $t['pasien']->update([
                'sph_r' => $t['sph_r'], 'cyl_r' => $t['cyl_r'], 'ax_r' => $t['ax_r'], 'add_r' => $t['add_r'],
                'sph_l' => $t['sph_l'], 'cyl_l' => $t['cyl_l'], 'ax_l' => $t['ax_l'], 'add_l' => $t['add_l'],
                'pd' => $t['pd'], 'last_exam_date' => $t['date'], 'no_resep' => 'RSP-00'.($idx+1)
            ]);

            // Create Transaksi
            $totalHarga = $t['frame']->harga_jual + $t['lensa']->harga_jual;
            $trx = Transaksi::create([
                'no_transaksi' => 'TRX-' . str_pad($idx + 1, 4, '0', STR_PAD_LEFT),
                'pasien_id' => $t['pasien']->id,
                'total_harga' => $totalHarga,
                'status' => 'selesai',
                'payment_method' => 'Tunai',
                'subtotal' => $totalHarga,
                'diskon' => 0,
                'pajak' => 0,
                'bayar' => $totalHarga,
                'bpjs_cover' => 0,
                'kembalian' => 0,
                'created_at' => $t['date'],
                'updated_at' => $t['date']
            ]);

            // Create Items
            TransaksiItem::create([
                'transaksi_id' => $trx->id,
                'produk_id' => $t['frame']->id,
                'qty' => 1,
                'harga' => $t['frame']->harga_jual
            ]);
            TransaksiItem::create([
                'transaksi_id' => $trx->id,
                'produk_id' => $t['lensa']->id,
                'qty' => 1,
                'harga' => $t['lensa']->harga_jual
            ]);

            // Create Riwayat Pemeriksaan
            RiwayatPemeriksaan::create([
                'pasien_id' => $t['pasien']->id,
                'transaksi_id' => $trx->id,
                'no_resep' => 'RSP-00'.($idx+1),
                'frame' => $t['frame']->nama,
                'lensa' => $t['lensa']->nama,
                'sph_r' => $t['sph_r'], 'cyl_r' => $t['cyl_r'], 'ax_r' => $t['ax_r'], 'add_r' => $t['add_r'],
                'sph_l' => $t['sph_l'], 'cyl_l' => $t['cyl_l'], 'ax_l' => $t['ax_l'], 'add_l' => $t['add_l'],
                'pd' => $t['pd'],
                'created_at' => $t['date'],
                'updated_at' => $t['date']
            ]);
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
