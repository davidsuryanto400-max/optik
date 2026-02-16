<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Kategori;

class KategoriController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->search;

        $tipes = \App\Models\Tipe::with([
            'kategoris' => function ($q) use ($search) {
                if ($search) {
                    $q->where('nama', 'like', '%' . $search . '%');
                }
            }
        ])
            ->where(function ($q) use ($search) {
                if ($search) {
                    $q->where('nama', 'like', '%' . $search . '%')
                        ->orWhereHas('kategoris', function ($q2) use ($search) {
                            $q2->where('nama', 'like', '%' . $search . '%');
                        });
                } else {
                    $q->has('kategoris');
                }
            })
            ->paginate(10);

        $all_tipes = \App\Models\Tipe::all();

        return view('kategori.index', compact('tipes', 'all_tipes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // View handled by modal
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'tipe_id' => 'required|exists:tipes,id',
        ]);

        Kategori::create($request->all());

        return redirect()->route('kategori.index')
            ->with('success', 'Kategori berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Kategori $kategori)
    {
        return view('kategori.show', compact('kategori'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Kategori $kategori)
    {
        // View handled by modal
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Kategori $kategori)
    {
        $request->validate([
            'nama' => 'required',
            'tipe_id' => 'required|exists:tipes,id',
        ]);

        $kategori->update($request->all());

        return redirect()->route('kategori.index')
            ->with('success', 'Kategori berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Kategori $kategori)
    {
        $kategori->delete();

        return redirect()->route('kategori.index')
            ->with('success', 'Kategori berhasil dihapus.');
    }
}
