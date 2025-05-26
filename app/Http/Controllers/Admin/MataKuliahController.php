<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MataKuliah;
use App\Models\Dosen;
use Illuminate\Http\Request;

class MataKuliahController extends Controller
{
    public function index()
    {
        $mataKuliahs = MataKuliah::with('dosen')->paginate(8);
        return view('admin.mata_kuliah.index', compact('mataKuliahs'));
    }

    public function create()
    {
        $dosens = Dosen::all();
        return view('admin.mata_kuliah.create', compact('dosens'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode' => 'required|unique:mata_kuliahs,kode',
            'nama' => 'required|string|max:255',
            'sks' => 'required|integer|min:1',
        ]);
        MataKuliah::create($validated);
        return redirect()->route('admin.mata-kuliah.index')->with('success', 'Data berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $mataKuliah = MataKuliah::findOrFail($id);
        return view('admin.mata_kuliah.edit', compact('mataKuliah'));
    }

    public function update(Request $request, $id)
    {
        $mataKuliah = MataKuliah::findOrFail($id);
        $validated = $request->validate([
            'kode' => 'required|unique:mata_kuliahs,kode,' . $mataKuliah->id,
            'nama' => 'required|string|max:255',
            'sks' => 'required|integer|min:1',
        ]);
        $mataKuliah->update($validated);
        return redirect()->route('admin.mata-kuliah.index')->with('success', 'Data berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $mataKuliah = MataKuliah::findOrFail($id);
        $mataKuliah->delete();
        return redirect()->route('admin.mata-kuliah.index')->with('success', 'Data berhasil dihapus.');
    }
}