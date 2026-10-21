<?php

namespace App\Http\Controllers;

use App\Models\JadwalTanam;
use Illuminate\Http\Request;

class JadwalTanamController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $jadwalTanams = JadwalTanam::latest()->get();
        return view('jadwal_tanam.index', compact('jadwalTanams'));    
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('jadwal_tanam.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            'nama_tanaman' => 'required|string|max:255',
            'lokasi_lahan' => 'required|string|max:255',
            'tanggal_tanam' => 'required|date',
            'status' => 'nullable|string|max:255',
            'catatan' => 'nullable|string',
        ]);

        JadwalTanam::create($validate);

        return redirect()->route('jadwal-tanam.index')->with('success', 'Jadwal tanam berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(JadwalTanam $jadwalTanam)
    {
        return view('jadwal_tanam.show', compact('jadwalTanam'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(JadwalTanam $jadwalTanam)
    {
        return view('jadwal_tanam.edit', compact('jadwalTanam'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, JadwalTanam $jadwalTanam)
    {
        $validate = $request->validate([
            'nama_tanaman' => 'required|string|max:255',
            'lokasi_lahan' => 'required|string|max:255',
            'tanggal_tanam' => 'required|date',
            'status' => 'nullable|string|max:255',
            'catatan' => 'nullable|string',
        ]);

        $jadwalTanam->update($validate);

        return redirect()->route('jadwal-tanam.index')->with('success', 'Jadwal tanam berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(JadwalTanam $jadwalTanam)
    {
        $jadwalTanam->delete();
        return redirect()->route('jadwal-tanam.index')
        ->with('success', 'Jadwal tanam berhasil dihapus.');
    }
}
