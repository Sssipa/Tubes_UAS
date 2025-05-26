<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use App\Models\TahunAkademik;
use Illuminate\Http\Request;

class MahasiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $mahasiswas = Mahasiswa::with('tahunAkademik')->paginate(8);
        return view('admin.mahasiswa.index', compact('mahasiswas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $tahunAkademiks = TahunAkademik::all();
        return view('admin.mahasiswa.create', compact('tahunAkademiks'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nim' => 'required|unique:mahasiswas,nim',
            'nama' => 'required|string|max:255',
            'alamat' => 'nullable|string',
            'jenis_kelamin' => 'required|in:L,P',
            'email' => 'required|email|unique:mahasiswas,email',
            'telepon' => 'nullable|string|max:20',
            'tahun_akademik_id' => 'required|exists:tahun_akademiks,id_tahun_akademik',
        ]);

        Mahasiswa::create($validated);

        return redirect()->route('admin.mahasiswa.index')->with('success', 'Data mahasiswa berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $mahasiswa = Mahasiswa::with('tahunAkademik')->findOrFail($id);
        return view('admin.mahasiswa.show', compact('mahasiswa'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $mahasiswa = Mahasiswa::findOrFail($id);
        $tahunAkademiks = TahunAkademik::all();
        return view('admin.mahasiswa.edit', compact('mahasiswa', 'tahunAkademiks'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $mahasiswa = Mahasiswa::findOrFail($id);

        $validated = $request->validate([
            'nim' => 'required|unique:mahasiswas,nim,' . $mahasiswa->id_mahasiswa . ',id_mahasiswa',
            'nama' => 'required|string|max:255',
            'alamat' => 'nullable|string',
            'jenis_kelamin' => 'required|in:L,P',
            'email' => 'required|email|unique:mahasiswas,email,' . $mahasiswa->id_mahasiswa . ',id_mahasiswa',
            'telepon' => 'nullable|string|max:20',
            'tahun_akademik_id' => 'required|exists:tahun_akademiks,id_tahun_akademik',
        ]);

        $mahasiswa->update($validated);

        return redirect()->route('admin.mahasiswa.index')->with('success', 'Data mahasiswa berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $mahasiswa = Mahasiswa::findOrFail($id);
        $mahasiswa->delete();

        return redirect()->route('admin.mahasiswa.index')->with('success', 'Data mahasiswa berhasil dihapus.');
    }
}
