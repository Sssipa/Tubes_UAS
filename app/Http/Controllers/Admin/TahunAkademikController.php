<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TahunAkademik;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class TahunAkademikController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tahunAkademiks = TahunAkademik::orderBy('tahun', 'desc')->orderBy('semester', 'desc')->paginate(8);
        return view('admin.tahun_akademik.index', compact('tahunAkademiks'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.tahun_akademik.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'tahun' => 'required|string|max:9|regex:/^\d{4}\/\d{4}$/',
            'semester' => ['required', Rule::in(['Ganjil', 'Genap'])],
        ]);

        DB::transaction(function () use ($request) {
            if ($request->has('status_aktif') && $request->status_aktif == '1') {
                TahunAkademik::where('status_aktif', true)->update(['status_aktif' => false]);
            }

            TahunAkademik::create([
                'tahun' => $request->tahun,
                'semester' => $request->semester,
                'status_aktif' => $request->has('status_aktif') && $request->status_aktif == '1',
            ]);
        });

        return redirect()->route('admin.tahun-akademik.index')->with('success', 'Tahun Akademik berhasil ditambahkan.');
    }


    /**
     * Set the specified resource as active.
     */
    public function setAktif(TahunAkademik $tahunAkademik)
    {
        DB::transaction(function () use ($tahunAkademik) {
            TahunAkademik::where('status_aktif', true)->update(['status_aktif' => false]);

            $tahunAkademik->status_aktif = true;
            $tahunAkademik->save();
        });

        return redirect()->route('admin.tahun-akademik.index')->with('success', 'Status Tahun Akademik ' . $tahunAkademik->nama_tahun_akademik . ' berhasil diubah menjadi Aktif.');
    }
}