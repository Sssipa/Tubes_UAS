<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JadwalKuliah;
use App\Models\MataKuliah;
use App\Models\Dosen;
use App\Models\Ruangan;
use App\Models\TahunAkademik;

class JadwalKuliahController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $jadwals = JadwalKuliah::with(['mataKuliah', 'dosen', 'ruangan', 'tahunAkademik'])->paginate(10);
        return view('admin.jadwal_kuliah.index', compact('jadwals'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $mataKuliahs = MataKuliah::all();
        $dosens = Dosen::all();
        $ruangans = Ruangan::all();
        $tahunAkademiks = TahunAkademik::all();
        return view('admin.jadwal_kuliah.create', compact('mataKuliahs', 'dosens', 'ruangans', 'tahunAkademiks'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'mata_kuliah_id' => 'required|exists:mata_kuliahs,id_mata_kuliah',
            'dosen_id' => 'required|exists:dosens,id_dosen',
            'ruangan_id' => 'required|exists:ruangans,id_ruangan',
            'hari' => 'required|string|max:20',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'tahun_akademik_id' => 'required|exists:tahun_akademiks,id_tahun_akademik',
        ]);

        JadwalKuliah::create($validated);

        return redirect()->route('jadwal-kuliah.index')->with('success', 'Jadwal kuliah berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $jadwal = JadwalKuliah::with(['mataKuliah', 'dosen', 'ruangan', 'tahunAkademik'])->findOrFail($id);
        return view('admin.jadwal_kuliah.show', compact('jadwal'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $jadwal = JadwalKuliah::findOrFail($id);
        $mataKuliahs = MataKuliah::all();
        $dosens = Dosen::all();
        $ruangans = Ruangan::all();
        $tahunAkademiks = TahunAkademik::all();
        return view('admin.jadwal_kuliah.edit', compact('jadwal', 'mataKuliahs', 'dosens', 'ruangans', 'tahunAkademiks'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $jadwal = JadwalKuliah::findOrFail($id);

        $validated = $request->validate([
            'mata_kuliah_id' => 'required|exists:mata_kuliahs,id_mata_kuliah',
            'dosen_id' => 'required|exists:dosens,id_dosen',
            'ruangan_id' => 'required|exists:ruangans,id_ruangan',
            'hari' => 'required|string|max:20',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'tahun_akademik_id' => 'required|exists:tahun_akademiks,id_tahun_akademik',
        ]);

        $jadwal->update($validated);

        return redirect()->route('jadwal-kuliah.index')->with('success', 'Jadwal kuliah berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $jadwal = JadwalKuliah::findOrFail($id);
        $jadwal->delete();

        return redirect()->route('jadwal-kuliah.index')->with('success', 'Jadwal kuliah berhasil dihapus.');
    }
}