<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Gudang;

class GudangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->search;

        $cabangs = \App\Models\Cabang::with([
            'gudangs' => function ($q) use ($search) {
                $q->where('is_active', true);
                if ($search) {
                    $q->where('nama', 'like', '%' . $search . '%');
                }
            }
        ])
            ->where('is_active', true)
            ->where(function ($q) use ($search) {
                if ($search) {
                    $q->where('nama', 'like', '%' . $search . '%')
                        ->orWhereHas('gudangs', function ($q2) use ($search) {
                            $q2->where('is_active', true)->where('nama', 'like', '%' . $search . '%');
                        });
                } else {
                    $q->has('gudangs');
                }
            })
            ->paginate(10);

        $all_cabangs = \App\Models\Cabang::where('is_active', true)->get();

        return view('gudang.index', compact('cabangs', 'all_cabangs'));
    }

    public function create()
    {
        // View handled by modal
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'cabang_id' => 'required|exists:cabangs,id',
            'alamat' => 'nullable',
        ]);

        Gudang::create($request->all());

        return redirect()->route('gudang.index')
            ->with('success', 'Gudang berhasil ditambahkan.');
    }

    public function show(Gudang $gudang)
    {
        return view('gudang.show', compact('gudang'));
    }

    public function edit(Gudang $gudang)
    {
        // View handled by modal
    }

    public function update(Request $request, Gudang $gudang)
    {
        $request->validate([
            'nama' => 'required',
            'cabang_id' => 'required|exists:cabangs,id',
            'alamat' => 'nullable',
        ]);

        $gudang->update($request->all());

        return redirect()->route('gudang.index')
            ->with('success', 'Gudang berhasil diperbarui.');
    }

    public function destroy(Gudang $gudang)
    {
        $gudang->update(['is_active' => false]);

        return redirect()->route('gudang.index')
            ->with('success', 'Gudang berhasil dinonaktifkan.');
    }
}
