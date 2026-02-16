<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\Tipe;
use App\Models\Kategori;

class ProdukController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->search;

        $tipes = Tipe::with([
            'produks' => function ($q) use ($search) {
                if ($search) {
                    $q->where('nama', 'like', '%' . $search . '%')
                        ->orWhere('merek', 'like', '%' . $search . '%')
                        ->orWhereHas('gudang', function ($q2) use ($search) {
                            $q2->where('nama', 'like', '%' . $search . '%');
                        });
                }
            },
            'produks.gudang',
            'produks.kategori'
        ])
            ->where(function ($q) use ($search) {
                if ($search) {
                    $q->whereHas('produks', function ($q2) use ($search) {
                        $q2->where('nama', 'like', '%' . $search . '%')
                            ->orWhere('merek', 'like', '%' . $search . '%')
                            ->orWhereHas('gudang', function ($q3) use ($search) {
                                $q3->where('nama', 'like', '%' . $search . '%');
                            });
                    });
                } else {
                    $q->has('produks');
                }
            })
            ->paginate(10);

        $all_tipes = Tipe::all();
        $all_kategoris = Kategori::all();
        $all_gudangs = \App\Models\Gudang::where('is_active', true)->get();

        return view('produk.index', compact('tipes', 'all_tipes', 'all_kategoris', 'all_gudangs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Handled by modal
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'kode' => 'nullable|unique:produks,kode',
            'nama' => 'required',
            'tipe_id' => 'required|exists:tipes,id',
            'kategori_id' => 'required|exists:kategoris,id',
            'gudang_id' => 'required|exists:gudangs,id',
            'harga_beli' => 'required|numeric',
            'harga_jual' => 'required|numeric',
            'stok' => 'required|integer',
            'stok_minimum' => 'required|integer',
        ]);

        // Auto generate kode if empty
        if (!$request->kode) {
            $request->merge(['kode' => 'PRD-' . time()]);
        }

        $produk = Produk::create($request->all());

        if ($request->stok > 0) {
            \App\Models\StockHistory::create([
                'produk_id' => $produk->id,
                'user_id' => auth()->id(),
                'tipe' => 'Stok Awal',
                'jumlah' => $request->stok,
                'stok_awal' => 0,
                'stok_akhir' => $request->stok,
                'catatan' => 'Stok Awal',
                'created_by' => auth()->user() ? auth()->user()->name : 'Admin',
                'modified_by' => auth()->user() ? auth()->user()->name : 'Admin',
            ]);
        }

        return redirect()->route('produk.index')
            ->with('success', 'Produk berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    /**
     * Display the specified resource.
     */
    public function show(Produk $produk)
    {
        try {
            $produk->load(['tipe', 'kategori', 'gudang', 'stockHistories.user']);

            if (request()->ajax()) {
                return view('produk.show_modal', compact('produk'));
            }
            return view('produk.show', compact('produk'));
        } catch (\Exception $e) {
            \Log::error("Error in ProdukController@show: " . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Update stock with history and password verification.
     */
    public function updateStock(Request $request, Produk $produk)
    {
        $request->validate([
            'tipe_update' => 'required|in:masuk,keluar',
            'jumlah' => 'required|integer|min:1',
            'catatan' => 'nullable|string',
            'password' => 'required|string',
        ]);

        if ($request->password !== 'admin123') {
            return back()->withErrors(['password' => 'Password salah!'])->withInput();
        }

        $stok_awal = $produk->stok;
        $jumlah = $request->jumlah;
        $stok_akhir = $stok_awal;
        $tipe_history = '';

        if ($request->tipe_update == 'masuk') {
            $stok_akhir = $stok_awal + $jumlah;
            $tipe_history = 'Masuk';
        } else {
            if ($stok_awal < $jumlah) {
                return back()->withErrors(['jumlah' => 'Stok tidak mencukupi!'])->withInput();
            }
            $stok_akhir = $stok_awal - $jumlah;
            $tipe_history = 'Keluar (Manual)';
        }

        \DB::transaction(function () use ($produk, $stok_akhir, $tipe_history, $jumlah, $stok_awal, $request) {
            $produk->update(['stok' => $stok_akhir]);

            \App\Models\StockHistory::create([
                'produk_id' => $produk->id,
                'user_id' => auth()->id(),
                'tipe' => $tipe_history,
                'jumlah' => $jumlah,
                'stok_awal' => $stok_awal,
                'stok_akhir' => $stok_akhir,
                'catatan' => $request->catatan,
                'created_by' => auth()->user() ? auth()->user()->name : 'Admin',
                'modified_by' => auth()->user() ? auth()->user()->name : 'Admin',
            ]);
        });

        return back()->with('success', 'Stok berhasil diperbarui.');
    }

    /**
     * Check password for stock update access.
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

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Produk $produk)
    {
        // Handled by modal
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Produk $produk)
    {
        $request->validate([
            'kode' => 'nullable|unique:produks,kode,' . $produk->id,
            'nama' => 'required',
            'tipe_id' => 'required|exists:tipes,id',
            'kategori_id' => 'required|exists:kategoris,id',
            'gudang_id' => 'required|exists:gudangs,id',
            'harga_beli' => 'required|numeric',
            'harga_jual' => 'required|numeric',
            'stok' => 'required|integer',
            'stok_minimum' => 'required|integer',
        ]);

        $produk->update($request->all());

        return redirect()->route('produk.index')
            ->with('success', 'Produk berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Produk $produk)
    {
        $produk->delete();

        return redirect()->route('produk.index')
            ->with('success', 'Produk berhasil dihapus.');
    }
}
