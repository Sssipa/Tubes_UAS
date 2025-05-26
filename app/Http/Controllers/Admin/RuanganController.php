<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ruangan;

class RuanganController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ruangans = Ruangan::paginate(10);
        return view('admin.ruangan.index', compact('ruangans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.ruangan.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode' => 'required|unique:ruangans,kode',
            'nama' => 'required|string|max:255',
            'kapasitas' => 'required|integer|min:1',
        ]);

        Ruangan::create($validated);

        return redirect()->route('admin.ruangan.index')->with('success', 'Data ruangan berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $ruangan = Ruangan::findOrFail($id);
        return view('admin.ruangan.show', compact('ruangan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $ruangan = Ruangan::findOrFail($id);
        return view('admin.ruangan.edit', compact('ruangan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $ruangan = Ruangan::findOrFail($id);

        $validated = $request->validate([
            'kode' => 'required|unique:ruangans,kode,' . $ruangan->id_ruangan . ',id_ruangan',
            'nama' => 'required|string|max:255',
            'kapasitas' => 'required|integer|min:1',
        ]);

        $ruangan->update($validated);

        return redirect()->route('admin.ruangan.index')->with('success', 'Data ruangan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $ruangan = Ruangan::findOrFail($id);
        $ruangan->delete();

        return redirect()->route('admin.ruangan.index')->with('success', 'Data ruangan berhasil dihapus.');
    }
}