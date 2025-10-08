<?php

namespace App\Http\Controllers;

use App\Models\Perangkat;
use Illuminate\Http\Request;

class PerangkatController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->query('q', '');
        $query = Perangkat::query();
        if ($q !== '') {
            $query->where(function ($sub) use ($q) {
                $sub->where('nama', 'like', "%{$q}%")
                    ->orWhere('tipe', 'like', "%{$q}%")
                    ->orWhere('lokasi', 'like', "%{$q}%");
            });
        }
        $rows = $query->orderByDesc('id')->get();

        return view('perangkat.index', [
            'rows' => $rows,
            'keyword' => $q,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama' => 'required|string|max:100',
            'tipe' => 'required|string|max:50',
            'lokasi' => 'required|string|max:150',
            'status' => 'required|in:aktif,tidak_aktif',
        ]);

        Perangkat::create($data);

        return redirect()->route('perangkat.index')->with('flash', 'Perangkat berhasil ditambahkan.');
    }

    public function edit(Perangkat $perangkat)
    {
        $rows = Perangkat::orderByDesc('id')->get();
        return view('perangkat.index', [
            'rows' => $rows,
            'editRow' => $perangkat,
            'keyword' => '',
        ]);
    }

    public function update(Request $request, Perangkat $perangkat)
    {
        $data = $request->validate([
            'nama' => 'required|string|max:100',
            'tipe' => 'required|string|max:50',
            'lokasi' => 'required|string|max:150',
            'status' => 'required|in:aktif,tidak_aktif',
        ]);

        $perangkat->update($data);

        return redirect()->route('perangkat.index')->with('flash', 'Perangkat berhasil diperbarui.');
    }

    public function destroy(Perangkat $perangkat)
    {
        $perangkat->delete();
        return redirect()->route('perangkat.index')->with('flash', 'Perangkat berhasil dihapus.');
    }
}
