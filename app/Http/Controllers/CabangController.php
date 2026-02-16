<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cabang;

class CabangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Cabang::where('is_active', true);

        if ($request->has('search')) {
            $query->where('nama', 'like', '%' . $request->search . '%');
        }

        $cabangs = $query->paginate(10);
        return view('cabang.index', compact('cabangs'));
    }

    public function create()
    {
        // View handled by modal
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'alamat' => 'nullable',
        ]);

        Cabang::create($request->all());

        return redirect()->route('cabang.index')
            ->with('success', 'Cabang berhasil ditambahkan.');
    }

    public function show(Cabang $cabang)
    {
        return view('cabang.show', compact('cabang'));
    }

    public function edit(Cabang $cabang)
    {
        // View handled by modal (data passed via JS)
    }

    public function update(Request $request, Cabang $cabang)
    {
        $request->validate([
            'nama' => 'required',
            'alamat' => 'nullable',
        ]);

        $cabang->update($request->all());

        return redirect()->route('cabang.index')
            ->with('success', 'Cabang berhasil diperbarui.');
    }

    public function destroy(Cabang $cabang)
    {
        $cabang->update(['is_active' => false]);

        return redirect()->route('cabang.index')
            ->with('success', 'Cabang berhasil dinonaktifkan.');
    }

}
