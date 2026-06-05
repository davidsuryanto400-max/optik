<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pasien;

class PasienController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->search;
        $filter = $request->filter;

        $pasiens = Pasien::with([
            'transaksis' => function ($q) {
                $q->latest();
            }
        ])
            ->when($search, function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('no_hp', 'like', "%{$search}%")
                    ->orWhere('alamat', 'like', "%{$search}%");
            })
            ->when($filter == 'birthday', function ($q) {
                $today = now();
                $nextMonth = now()->addDays(30);

                // Logic for upcoming birthdays (ignoring year)
                // We check if the day of year is between today and 30 days from now
                $startDay = $today->dayOfYear;
                $endDay = $nextMonth->dayOfYear;

                if ($endDay >= $startDay) {
                    $q->whereRaw("DAYOFYEAR(tgl_lahir) BETWEEN ? AND ?", [$startDay, $endDay]);
                } else {
                    // Wrap around year end
                    $q->where(function ($sub) use ($startDay, $endDay) {
                        $sub->whereRaw("DAYOFYEAR(tgl_lahir) >= ?", [$startDay])
                            ->orWhereRaw("DAYOFYEAR(tgl_lahir) <= ?", [$endDay]);
                    });
                }
            })
            ->latest()
            ->paginate(10);

        // Count upcoming birthdays for the badge
        $today = now();
        $nextMonth = now()->addDays(30);
        $startDay = $today->dayOfYear;
        $endDay = $nextMonth->dayOfYear;

        $birthdayQuery = Pasien::query();
        if ($endDay >= $startDay) {
            $birthdayQuery->whereRaw("DAYOFYEAR(tgl_lahir) BETWEEN ? AND ?", [$startDay, $endDay]);
        } else {
            $birthdayQuery->where(function ($sub) use ($startDay, $endDay) {
                $sub->whereRaw("DAYOFYEAR(tgl_lahir) >= ?", [$startDay])
                    ->orWhereRaw("DAYOFYEAR(tgl_lahir) <= ?", [$endDay]);
            });
        }
        $upcomingBirthdaysCount = $birthdayQuery->count();

        return view('pasien.index', compact('pasiens', 'upcomingBirthdaysCount'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'no_hp' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
            'tgl_lahir' => 'nullable|date',
            'last_exam_date' => 'nullable|date',
            'sph_r' => 'nullable|numeric',
            'cyl_r' => 'nullable|numeric',
            'ax_r' => 'nullable|string|max:10',
            'add_r' => 'nullable|string|max:10',
            'sph_l' => 'nullable|numeric',
            'cyl_l' => 'nullable|numeric',
            'ax_l' => 'nullable|string|max:10',
            'add_l' => 'nullable|string|max:10',
            'pd' => 'nullable|string|max:10',
        ]);

        $pasien = Pasien::create($request->all());

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Pasien berhasil ditambahkan.',
                'data' => $pasien
            ]);
        }

        return redirect()->route('pasien.index')
            ->with('success', 'Pasien berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $pasien = Pasien::findOrFail($id);

        // Return JSON for AJAX modal if requested, otherwise view
        if (request()->wantsJson()) {
            return response()->json($pasien);
        }

        return view('pasien.show', compact('pasien'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Not used, using modal
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'no_hp' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
            'tgl_lahir' => 'nullable|date',
            'last_exam_date' => 'nullable|date',
            'sph_r' => 'nullable|numeric',
            'cyl_r' => 'nullable|numeric',
            'ax_r' => 'nullable|string|max:10',
            'add_r' => 'nullable|string|max:10',
            'sph_l' => 'nullable|numeric',
            'cyl_l' => 'nullable|numeric',
            'ax_l' => 'nullable|string|max:10',
            'add_l' => 'nullable|string|max:10',
            'pd' => 'nullable|string|max:10',
        ]);

        $pasien = Pasien::findOrFail($id);
        $pasien->update($request->all());

        return redirect()->route('pasien.index')
            ->with('success', 'Data pasien berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $pasien = Pasien::findOrFail($id);
        $pasien->delete();

        return redirect()->route('pasien.index')
            ->with('success', 'Pasien berhasil dihapus.');
    }

    public function print(string $id)
    {
        $pasien = Pasien::findOrFail($id);
        return view('pasien.print', compact('pasien'));
    }
}
