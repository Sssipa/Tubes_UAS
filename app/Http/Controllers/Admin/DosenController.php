<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Dosen;

class DosenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $dosens = Dosen::paginate(8);
        return view('admin.dosen.index', compact('dosens'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    { 
        return view('admin.dosen.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nidn' => 'required|unique:dosens',
            'nama' => 'required',
            'alamat' => 'required',
            'email' => 'required|email|unique:dosens',
            'telepon' => 'required',
        ]);

        Dosen::create([
            'nidn' => $validated['nidn'],
            'nama' => $validated['nama'],
            'alamat' => $validated['alamat'],
            'email' => $validated['email'],
            'telepon' => $validated['telepon'],
        ]);

        return redirect()->route('admin.dosen.index')->with('success', 'Data berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $dosen = Dosen::findOrFail($id);
        return view('admin.dosen.show', compact('dosen'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $dosen = Dosen::findOrFail($id);
        return view('admin.dosen.edit', compact('dosen'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $dosen = Dosen::findOrFail($id);
        $validated = $request->validate([
            'nidn' => 'required|unique:dosens,nidn,' . $dosen->id_dosen . ',id_dosen',
            'nama' => 'required',
            'alamat' => 'required',
            'email' => 'required|email|unique:dosens,email,' . $dosen->id_dosen . ',id_dosen',
            'telepon' => 'required',
        ]);
        $dosen->update([
            'nidn' => $validated['nidn'],
            'nama' => $validated['nama'],
            'alamat' => $validated['alamat'],
            'email' => $validated['email'],
            'telepon' => $validated['telepon'],
        ]);
        return redirect()->route('admin.dosen.index')->with('success', 'Data berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $dosen = Dosen::findOrFail($id);
        $dosen->delete();
        return redirect()->route('admin.dosen.index')->with('success', 'Data berhasil dihapus.');
    }
}
