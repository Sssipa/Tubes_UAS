<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\JadwalKuliah;
use App\Models\Krs;
use App\Models\PengajuanKrs;
use App\Models\TahunAkademik;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class JadwalController extends Controller
{
    /**
     * Menampilkan jadwal kuliah mahasiswa yang telah disetujui.
     */
    public function index()
    {
        $user = Auth::user();
        if (!$user || !$user->mahasiswa) {
            return redirect()->route('login')->with('error', 'Data mahasiswa tidak ditemukan. Silakan login kembali.');
        }
        $mahasiswa = $user->mahasiswa;

        $tahunAkademikAktif = TahunAkademik::where('status_aktif', true)->first();
        $jadwalKuliahMahasiswa = collect(); // Default ke koleksi kosong
        $pesanInfo = null;

        if (!$tahunAkademikAktif) {
            $pesanInfo = 'Saat ini tidak ada periode akademik yang aktif untuk menampilkan jadwal.';
        } else {
            // Cari PengajuanKrs yang sudah disetujui oleh admin akademik
            $pengajuanKrsDisetujui = PengajuanKrs::where('mahasiswa_id', $mahasiswa->id_mahasiswa)
                ->where('tahun_akademik_id', $tahunAkademikAktif->id_tahun_akademik)
                ->where('status_pengajuan', 'disetujui_akademik')
                ->first();

            if (!$pengajuanKrsDisetujui) {
                $pesanInfo = 'KRS Anda untuk periode ini belum disetujui atau tidak ditemukan. Jadwal belum dapat ditampilkan.';
            } else {
                // Ambil mata_kuliah_id dari tabel krs berdasarkan mahasiswa_id dan tahun_akademik_id
                // Asumsi Krs menyimpan semester_mahasiswa yang sesuai dengan pengajuan.
                // Jika PengajuanKrs memiliki semester_mahasiswa, bisa ditambahkan di where clause Krs.
                $mataKuliahIds = Krs::where('mahasiswa_id', $mahasiswa->id_mahasiswa)
                                    ->where('tahun_akademik_id', $tahunAkademikAktif->id_tahun_akademik)
                                    // Jika ingin lebih spesifik dengan semester mahasiswa pada Krs:
                                    // ->where('semester_mahasiswa', $pengajuanKrsDisetujui->semester_mahasiswa_pada_saat_itu)
                                    ->pluck('mata_kuliah_id')
                                    ->toArray();

                if (empty($mataKuliahIds)) {
                    $pesanInfo = 'Tidak ada mata kuliah yang terdaftar pada KRS Anda yang telah disetujui.';
                } else {
                    // Ambil jadwal kuliah untuk mata kuliah tersebut pada tahun akademik aktif
                    $jadwalKuliahMahasiswa = JadwalKuliah::with(['mataKuliah', 'dosen', 'ruangan'])
                        ->whereIn('mata_kuliah_id', $mataKuliahIds)
                        ->where('tahun_akademik_id', $tahunAkademikAktif->id_tahun_akademik)
                        ->orderByRaw("FIELD(hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu')")
                        ->orderBy('jam_mulai')
                        ->get();
                    
                    if($jadwalKuliahMahasiswa->isEmpty()){
                        $pesanInfo = 'Jadwal untuk mata kuliah Anda belum tersedia atau belum diatur oleh admin.';
                    }
                }
            }
        }

        return view('mahasiswa.jadwal.index', compact(
            'mahasiswa',
            'tahunAkademikAktif',
            'jadwalKuliahMahasiswa',
            'pesanInfo'
        ));
    }
}
