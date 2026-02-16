<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Tipe;

class TipeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Tipe::query();

        if ($request->has('search')) {
            $query->where('nama', 'like', '%' . $request->search . '%');
        }

        $tipes = $query->paginate(10);
        return view('tipe.index', compact('tipes'));
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
            'nama' => 'required|unique:tipes,nama',
        ]);

        Tipe::create($request->all());

        return redirect()->route('tipe.index')
            ->with('success', 'Tipe produk berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Tipe $tipe)
    {
        return view('tipe.show', compact('tipe'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tipe $tipe)
    {
        // View handled by modal
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tipe $tipe)
    {
        $request->validate([
            'nama' => 'required|unique:tipes,nama,' . $tipe->id,
        ]);

        $tipe->update($request->all());

        return redirect()->route('tipe.index')
            ->with('success', 'Tipe produk berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tipe $tipe)
    {
        $tipe->delete();

        return redirect()->route('tipe.index')
            ->with('success', 'Tipe produk berhasil dihapus.');
    }
}
