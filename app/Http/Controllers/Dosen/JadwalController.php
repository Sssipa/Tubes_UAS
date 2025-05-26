<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\JadwalKuliah;
use App\Models\TahunAkademik;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class JadwalController extends Controller
{
    /**
     * Menampilkan jadwal mengajar untuk dosen yang sedang login.
     */
    public function index()
    {
        if (!Auth::check() || !Auth::user()->dosen) {
            // Jika user tidak login atau tidak memiliki profil dosen yang terhubung
            return redirect()->route('auth.login')->with('error', 'Silakan login sebagai dosen terlebih dahulu.');
        }

        $user = Auth::user();
        $dosen = $user->dosen; // Mengambil data dosen dari relasi user

        if (!$dosen) {
            Log::warning('User ' . $user->id_user . ' tidak memiliki data dosen terkait saat mencoba akses Jadwal Mengajar.');
            return redirect()->route('home')
                            ->with('error', 'Data dosen tidak ditemukan. Harap hubungi administrator.');
        }

        // Ambil Tahun Akademik Aktif
        $tahunAkademikAktif = TahunAkademik::where('status_aktif', true)->first();

        if (!$tahunAkademikAktif) {
            return redirect()->route('dosen.dashboard')
                            ->with('warning', 'Saat ini tidak ada tahun akademik yang aktif. Jadwal tidak dapat ditampilkan.');
        }

        // Ambil Jadwal Kuliah untuk dosen pada tahun akademik aktif, lengkap dengan relasi
        $jadwalMengajar = JadwalKuliah::with(['mataKuliah', 'ruangan', 'tahunAkademik'])
            ->where('dosen_id', $dosen->id_dosen)
            ->where('tahun_akademik_id', $tahunAkademikAktif->id_tahun_akademik)
            ->orderBy('hari')
            ->orderBy('jam_mulai')
            ->get();

        return view('dosen.jadwal.index', compact(
            'dosen',
            'jadwalMengajar',
            'tahunAkademikAktif'
        ));
    }
}