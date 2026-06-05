<?php

namespace App\Http\Controllers;

use App\Models\Pasien;
use App\Models\Produk;
use App\Models\Transaksi;
use App\Models\TransaksiItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransaksiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Transaksi::with(['pasien', 'items.produk'])->latest();

        // Default to current month if no date is provided
        $startDate = $request->start_date ?? now()->startOfMonth()->toDateString();
        $endDate = $request->end_date ?? now()->toDateString();

        $query->whereDate('created_at', '>=', $startDate);
        $query->whereDate('created_at', '<=', $endDate);

        // Filter status: default to unvoid (selesai) unless lihat_void is checked
        $showVoid = $request->has('lihat_void');
        if ($showVoid) {
            $query->where('status', 'void');
        } else {
            $query->where('status', 'selesai');
        }

        $transaksis = $query->paginate(10)->withQueryString();

        return view('transaksi.index', compact('transaksis', 'startDate', 'endDate', 'showVoid'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // To be implemented
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'pasien_id' => 'required|exists:pasiens,id',
            'items' => 'required|array|min:1',
            'payment_method' => 'required',
            'bayar' => 'required|numeric|min:0',
        ]);

        \Illuminate\Support\Facades\Log::info('Transaction Store Request:', $request->all());

        DB::beginTransaction();
        try {
            // Updated Prescription Logic
            if ($request->has('new_prescription') && $request->new_prescription == 'true') {
                $pasien = Pasien::find($request->pasien_id);

                // 1. Update Current Pasien Data
                $pasien->update([
                    'sph_r' => $request->sph_r,
                    'cyl_r' => $request->cyl_r,
                    'ax_r' => $request->ax_r,
                    'add_r' => $request->add_r,
                    'sph_l' => $request->sph_l,
                    'cyl_l' => $request->cyl_l,
                    'ax_l' => $request->ax_l,
                    'add_l' => $request->add_l,
                    'pd' => $request->pd,
                    'last_exam_date' => now(),
                ]);

                // 2. Create History Record
                \App\Models\RiwayatPemeriksaan::create([
                    'pasien_id' => $pasien->id,
                    'transaksi_id' => null, // Will update after transaction created
                    'sph_r' => $request->sph_r,
                    'cyl_r' => $request->cyl_r,
                    'ax_r' => $request->ax_r,
                    'add_r' => $request->add_r,
                    'sph_l' => $request->sph_l,
                    'cyl_l' => $request->cyl_l,
                    'ax_l' => $request->ax_l,
                    'add_l' => $request->add_l,
                    'pd' => $request->pd,
                ]);
            }

            // Create Transaction
            $transaksi = Transaksi::create([
                'no_transaksi' => 'SALE-' . str_pad(Transaksi::max('id') + 1, 4, '0', STR_PAD_LEFT),
                'nota_manual' => $request->nota_manual,
                'pasien_id' => $request->pasien_id,
                'total_harga' => $request->grand_total,
                'status' => 'selesai', // Directly completed for POS
                'payment_method' => $request->payment_method,
                'subtotal' => $request->subtotal,
                'diskon' => $request->diskon ?? 0,
                'pajak' => $request->ppn ?? 0,
                'bayar' => $request->bayar,
                'bpjs_cover' => $request->bpjs_cover ?? 0,
                'kembalian' => $request->kembalian,
                'created_at' => now(),
            ]);

            // Link History to Transaction if applicable
            if ($request->has('new_prescription') && $request->new_prescription == 'true') {
                $latestHistory = \App\Models\RiwayatPemeriksaan::where('pasien_id', $request->pasien_id)
                    ->latest()->first();
                if ($latestHistory) {
                    $latestHistory->update(['transaksi_id' => $transaksi->id]);
                }
            }

            // Create Items
            foreach ($request->items as $item) {
                TransaksiItem::create([
                    'transaksi_id' => $transaksi->id,
                    'produk_id' => $item['id'],
                    'qty' => $item['qty'],
                    'harga' => $item['price'],
                ]);

                // Decrement Stock
                $produk = Produk::find($item['id']);
                if ($produk) {
                    $stok_awal = $produk->stok;
                    $produk->decrement('stok', $item['qty']);
                    $stok_akhir = $produk->stok;

                    \App\Models\StockHistory::create([
                        'produk_id' => $produk->id,
                        'user_id' => auth()->id(),
                        'tipe' => 'Keluar (Penjualan)',
                        'jumlah' => $item['qty'],
                        'stok_awal' => $stok_awal,
                        'stok_akhir' => $stok_akhir,
                        'catatan' => 'Penjualan No: ' . $transaksi->no_transaksi,
                        'created_by' => auth()->user() ? auth()->user()->name : 'Admin',
                        'modified_by' => auth()->user() ? auth()->user()->name : 'Admin',
                    ]);
                }
            }

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Transaksi berhasil disimpan', 'id' => $transaksi->id]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Gagal menyimpan transaksi: ' . $e->getMessage()], 500);
        }
    }

    public function getProducts(Request $request)
    {
        $search = $request->q;
        $type = $request->type; // 1 (Frame frontend), 2 (Lensa frontend), 'all' (Others)

        $query = Produk::query();

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('kode', 'like', "%{$search}%");
            });
        }

        if ($type == '1') {
            // Frontend requests Frame
            $query->whereHas('tipe', function($q) {
                $q->where('nama', 'like', '%Frame%');
            });
        } elseif ($type == '2') {
            // Frontend requests Lensa
            $query->whereHas('tipe', function($q) {
                $q->where('nama', 'like', '%Lensa%');
            });
        } elseif ($type == 'all') {
            // Frontend requests Others (not Frame and not Lensa)
            $query->whereHas('tipe', function($q) {
                $q->where('nama', 'not like', '%Frame%')->where('nama', 'not like', '%Lensa%');
            });
        } else if ($type) {
             $query->where('tipe_id', $type);
        }

        $products = $query->limit(20)->get(['id', 'nama', 'harga_jual', 'stok']);

        return response()->json($products);
    }

    public function getPasiens(Request $request)
    {
        $search = $request->q;
        $query = Pasien::query();

        if ($search) {
            $query->where('nama', 'like', "%{$search}%")
                ->orWhere('no_hp', 'like', "%{$search}%");
        }

        $pasiens = $query->limit(10)->get();
        return response()->json($pasiens);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // To be implemented
    }

    public function print($id)
    {
        $transaksi = Transaksi::with(['items.produk', 'pasien'])->findOrFail($id);
        return view('transaksi.print', compact('transaksi'));
    }



    /**
     * Update the specified resource in storage.
     */
    public function checkPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|string',
        ]);

        if ($request->password !== 'admin123') {
            return response()->json(['valid' => false, 'message' => 'Password salah!'], 401);
        }

        return response()->json(['valid' => true]);
    }

    public function edit($id)
    {
        $transaksi = Transaksi::with(['items.produk', 'pasien'])->findOrFail($id);
        return view('transaksi.edit', compact('transaksi'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'pasien_id' => 'required|exists:pasiens,id',
            'items' => 'required|array|min:1',
            'payment_method' => 'required',
            'bayar' => 'required|numeric|min:0',
        ]);

        \Illuminate\Support\Facades\Log::info('Transaction Update Request:', $request->all());

        DB::beginTransaction();
        try {
            $transaksi = Transaksi::findOrFail($id);

            // 1. Restore Stock for OLD items
            foreach ($transaksi->items as $oldItem) {
                $produk = Produk::find($oldItem->produk_id);
                if ($produk) {
                    $stok_awal = $produk->stok;
                    $produk->increment('stok', $oldItem->qty);
                    $stok_akhir = $produk->stok;

                    \App\Models\StockHistory::create([
                        'produk_id' => $produk->id,
                        'user_id' => auth()->id(),
                        'tipe' => 'Masuk (Edit)',
                        'jumlah' => $oldItem->qty,
                        'stok_awal' => $stok_awal,
                        'stok_akhir' => $stok_akhir,
                        'catatan' => 'Restorasi stok dari Edit Penjualan No: ' . $transaksi->no_transaksi,
                        'created_by' => auth()->user() ? auth()->user()->name : 'Admin',
                        'modified_by' => auth()->user() ? auth()->user()->name : 'Admin',
                    ]);
                }
            }

            // 2. Delete OLD items
            $transaksi->items()->delete();

            // 3. Update Transaction Details
            $transaksi->update([
                'nota_manual' => $request->nota_manual,
                'pasien_id' => $request->pasien_id,
                'total_harga' => $request->grand_total,
                'payment_method' => $request->payment_method,
                'subtotal' => $request->subtotal,
                'diskon' => $request->diskon ?? 0,
                'pajak' => $request->ppn ?? 0,
                'bayar' => $request->bayar,
                'bpjs_cover' => $request->bpjs_cover ?? 0,
                'kembalian' => $request->kembalian,
            ]);

            // 4. Create NEW items & Deduct Stock
            foreach ($request->items as $item) {
                TransaksiItem::create([
                    'transaksi_id' => $transaksi->id,
                    'produk_id' => $item['id'],
                    'qty' => $item['qty'],
                    'harga' => $item['price'],
                ]);

                // Decrement Stock
                $produk = Produk::find($item['id']);
                if ($produk) {
                    $stok_awal = $produk->stok;
                    $produk->decrement('stok', $item['qty']);
                    $stok_akhir = $produk->stok;

                    \App\Models\StockHistory::create([
                        'produk_id' => $produk->id,
                        'user_id' => auth()->id(),
                        'tipe' => 'Keluar (Edit)',
                        'jumlah' => $item['qty'],
                        'stok_awal' => $stok_awal,
                        'stok_akhir' => $stok_akhir,
                        'catatan' => 'Penyesuaian stok dari Edit Penjualan No: ' . $transaksi->no_transaksi,
                        'created_by' => auth()->user() ? auth()->user()->name : 'Admin',
                        'modified_by' => auth()->user() ? auth()->user()->name : 'Admin',
                    ]);
                }
            }

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Transaksi berhasil diperbarui']);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Gagal memperbarui transaksi: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        DB::beginTransaction();
        try {
            $transaksi = Transaksi::findOrFail($id);

            // Prevent double voiding
            if ($transaksi->status === 'void') {
                return response()->json(['success' => false, 'message' => 'Transaksi sudah divoid sebelumnya'], 400);
            }

            // Restore Stock
            foreach ($transaksi->items as $item) {
                $produk = Produk::find($item->produk_id);
                if ($produk) {
                    $stok_awal = $produk->stok;
                    $produk->increment('stok', $item->qty);
                    $stok_akhir = $produk->stok;

                    \App\Models\StockHistory::create([
                        'produk_id' => $produk->id,
                        'user_id' => auth()->id(),
                        'tipe' => 'Masuk (Void)',
                        'jumlah' => $item->qty,
                        'stok_awal' => $stok_awal,
                        'stok_akhir' => $stok_akhir,
                        'catatan' => 'Void Penjualan No: ' . $transaksi->no_transaksi,
                        'created_by' => auth()->user() ? auth()->user()->name : 'Admin',
                        'modified_by' => auth()->user() ? auth()->user()->name : 'Admin',
                    ]);
                }
            }

            // Update status to void instead of deleting
            $transaksi->update(['status' => 'void']);

            DB::commit();

            if (request()->wantsJson()) {
                return response()->json(['success' => true, 'message' => 'Transaksi berhasil divoid (dibatalkan)']);
            }

            return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil divoid');
        } catch (\Exception $e) {
            DB::rollBack();
            if (request()->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Gagal menghapus transaksi: ' . $e->getMessage()], 500);
            }
            return redirect()->route('transaksi.index')->with('error', 'Gagal menghapus transaksi: ' . $e->getMessage());
        }
    }

    private function seedData()
    {
        // Seed specific patients from design
        $patientsData = [
            ['nama' => 'Sari Santoso', 'no_hp' => '081234567890', 'alamat' => 'Jl. Merdeka No. 10', 'sph_r' => -1.00, 'cyl_r' => -0.50, 'sph_l' => -1.25, 'cyl_l' => 0.00, 'pd' => '62'],
            ['nama' => 'Fajar Santoso', 'no_hp' => '081234567891', 'alamat' => 'Jl. Sudirman No. 5', 'sph_r' => -2.00, 'cyl_r' => -1.00, 'sph_l' => -2.00, 'cyl_l' => -1.00, 'pd' => '64'],
            ['nama' => 'Budi Santoso', 'no_hp' => '081234567892', 'alamat' => 'Jl. Thamrin No. 2', 'sph_r' => 0.00, 'cyl_r' => 0.00, 'sph_l' => -0.50, 'cyl_l' => 0.00, 'pd' => '63'],
            ['nama' => 'Bagus Setiawan', 'no_hp' => '081234567893', 'alamat' => 'Jl. Gatot Subroto', 'sph_r' => -3.50, 'cyl_r' => -1.25, 'sph_l' => -3.25, 'cyl_l' => -1.50, 'pd' => '60'],
            ['nama' => 'Rahmat Pratama', 'no_hp' => '081234567894', 'alamat' => 'Jl. Asia Afrika', 'sph_r' => -0.75, 'cyl_r' => 0.00, 'sph_l' => -0.75, 'cyl_l' => 0.00, 'pd' => '65'],
            ['nama' => 'Nina Kusuma', 'no_hp' => '081234567895', 'alamat' => 'Jl. Pahlawan', 'sph_r' => -5.00, 'cyl_r' => -2.00, 'sph_l' => -4.50, 'cyl_l' => -2.25, 'pd' => '61'],
            ['nama' => 'Adi Wijaya', 'no_hp' => '081234567896', 'alamat' => 'Jl. Diponegoro', 'sph_r' => -1.50, 'cyl_r' => -0.50, 'sph_l' => -1.50, 'cyl_l' => -0.50, 'pd' => '63'],
        ];

        foreach ($patientsData as $p) {
            Pasien::updateOrCreate(['nama' => $p['nama']], $p);
        }

        // Ensure we have some products
        if (Produk::count() == 0) {
            $prods = ['DriveSafe 1.67', 'EyeZen 1.67', 'FreshLook Monthly', 'Wayfarer Carrera', 'Frogskins Police', 'Holbrook Oakley', 'Erika Oakley', 'Lens Cleaning Kit'];
            foreach ($prods as $name) {
                Produk::create([
                    'nama' => $name,
                    'kategori_id' => 1, // Assuming 1 exists or fails gracefully (should check but ok for seed)
                    'tipe_id' => 1,
                    'gudang_id' => 1,
                    'stok' => 100,
                    'harga_jual' => 1000000,
                    'harga_beli' => 800000
                ]);
            }
        }

        $pasiens = Pasien::all();
        $produks = Produk::all();

        if ($produks->isEmpty())
            return;

        $data = [
            [
                'no' => 'SALE-0093',
                'manual' => null,
                'pasien_name' => 'Sari Santoso',
                'date' => '2026-01-15',
                'items' => [
                    ['name' => 'DriveSafe 1.67 (Rodenstock)', 'qty' => 1, 'price' => 2500000],
                    ['name' => 'EyeZen 1.67 (Essilor)', 'qty' => 1, 'price' => 2606000],
                ],
                'method' => 'Tunai',
                'subtotal' => 5106000,
                'diskon' => 100000,
                'ppn' => 506000,
                'total' => 5512000,
                'bayar' => 5600000,
                'kembalian' => 54000
            ],
            [
                'no' => 'SALE-0085',
                'manual' => null,
                'pasien_name' => 'Fajar Santoso',
                'date' => '2025-12-02',
                'items' => [
                    ['name' => 'FreshLook Monthly (FreshLook)', 'qty' => 1, 'price' => 1000000],
                    ['name' => 'Wayfarer Carrera (Carrera)', 'qty' => 1, 'price' => 1000000],
                    ['name' => 'Frogskins Police (Police)', 'qty' => 1, 'price' => 1108000],
                ],
                'method' => 'Debit',
                'subtotal' => 3108000,
                'diskon' => 0,
                'ppn' => 308000,
                'total' => 3416000,
                'bayar' => 3418000,
                'kembalian' => 2000
            ],
            [
                'no' => 'SALE-0038',
                'manual' => null,
                'pasien_name' => 'Budi Santoso',
                'date' => '2025-11-28',
                'items' => [
                    ['name' => 'Holbrook Oakley (Oakley)', 'qty' => 1, 'price' => 2000000],
                    ['name' => 'Erika Oakley (Oakley)', 'qty' => 1, 'price' => 1918300],
                ],
                'method' => 'Kartu Kredit',
                'subtotal' => 3918300,
                'diskon' => 70000,
                'ppn' => 388300,
                'total' => 4236600,
                'bayar' => 4236600,
                'kembalian' => 0
            ],
            [
                'no' => 'SALE-0076',
                'manual' => null,
                'pasien_name' => 'Bagus Setiawan',
                'date' => '2025-11-19',
                'items' => [
                    ['name' => 'Lens Cleaning Kit (Generic)', 'qty' => 1, 'price' => 55500],
                ],
                'method' => 'Debit',
                'subtotal' => 55500,
                'diskon' => 0,
                'ppn' => 5500,
                'total' => 61000,
                'bayar' => 61000,
                'kembalian' => 0
            ],
        ];

        foreach ($data as $d) {
            $pasien = Pasien::where('nama', $d['pasien_name'])->first() ?? $pasiens->first();

            // Check if transaction exists
            if (Transaksi::where('no_transaksi', $d['no'])->exists())
                continue;

            $trx = Transaksi::create([
                'no_transaksi' => $d['no'],
                'nota_manual' => $d['manual'],
                'pasien_id' => $pasien->id,
                'total_harga' => $d['total'],
                'status' => 'selesai',
                'payment_method' => $d['method'],
                'subtotal' => $d['subtotal'],
                'diskon' => $d['diskon'],
                'pajak' => $d['ppn'],
                'bayar' => $d['bayar'],
                'kembalian' => $d['kembalian'],
                'created_at' => $d['date'],
                'updated_at' => $d['date'],
            ]);

            foreach ($d['items'] as $item) {
                // Find or create temp product if not exists
                $produkName = explode(' ', $item['name'])[0];
                $produk = Produk::where('nama', 'like', '%' . $produkName . '%')->first() ?? $produks->first();

                TransaksiItem::create([
                    'transaksi_id' => $trx->id,
                    'produk_id' => $produk->id,
                    'qty' => $item['qty'],
                    'harga' => $item['price'],
                ]);
            }
        }
    }
}
