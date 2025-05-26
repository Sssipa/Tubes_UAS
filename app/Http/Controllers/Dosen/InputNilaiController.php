<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Dosen;
use App\Models\JadwalKuliah;
use App\Models\TahunAkademik;
use App\Models\Krs;
use App\Models\Khs;
use App\Models\PengajuanKrs; // Untuk cek status persetujuan
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class InputNilaiController extends Controller
{
    // Skala bobot nilai (bisa diletakkan di helper atau service)
    protected function getSkalaNilai() {
        return ['A+', 'A', 'A-', 'B+', 'B', 'B-', 'C', 'D', 'E', 'T'];
    }

    /**
     * Menampilkan daftar mata kuliah yang diajar dosen untuk input nilai.
     */
    public function index()
    {
        $user = Auth::user();
        if (!$user || !$user->dosen) {
            return redirect()->route('login')->with('error', 'Data dosen tidak ditemukan.');
        }
        $dosen = $user->dosen;

        $tahunAkademikAktif = TahunAkademik::where('status_aktif', true)->first();
        $jadwalDiajar = collect();
        $pesanInfo = null;

        if (!$tahunAkademikAktif) {
            $pesanInfo = 'Saat ini tidak ada periode akademik yang aktif.';
        } else {
            $jadwalDiajar = JadwalKuliah::with(['mataKuliah', 'ruangan', 'tahunAkademik'])
                ->where('dosen_id', $dosen->id_dosen)
                ->where('tahun_akademik_id', $tahunAkademikAktif->id_tahun_akademik)
                ->orderBy('hari')
                ->orderBy('jam_mulai')
                ->get();
            if($jadwalDiajar->isEmpty()){
                $pesanInfo = 'Anda tidak memiliki jadwal mengajar pada periode ini.';
            }
        }

        return view('dosen.input_nilai.index', compact('dosen', 'jadwalDiajar', 'tahunAkademikAktif', 'pesanInfo'));
    }

    /**
     * Menampilkan form untuk input/edit nilai untuk kelas tertentu.
     * Parameter $id_jadwal_kuliah adalah ID dari tabel jadwal_kuliahs.
     */
    public function showKelas($id_jadwal_kuliah)
    {
        $user = Auth::user();
        if (!$user || !$user->dosen) {
            return redirect()->route('login')->with('error', 'Data dosen tidak ditemukan.');
        }
        $dosen = $user->dosen;

        $jadwalKuliah = JadwalKuliah::with(['mataKuliah', 'tahunAkademik'])
            ->where('dosen_id', $dosen->id_dosen) // Pastikan dosen hanya bisa akses jadwalnya sendiri
            ->findOrFail($id_jadwal_kuliah);

        $mata_kuliah_id = $jadwalKuliah->mata_kuliah_id;
        $tahun_akademik_id = $jadwalKuliah->tahun_akademik_id;

        // Ambil mahasiswa yang mengambil mata kuliah ini di tahun akademik ini
        // dan KRS-nya sudah disetujui admin
        $krsEntries = Krs::with(['mahasiswa.user'])
            ->where('mata_kuliah_id', $mata_kuliah_id)
            ->where('tahun_akademik_id', $tahun_akademik_id)
            ->whereHas('pengajuanKrsMahasiswa', function ($query) use ($tahun_akademik_id) {
                // Asumsi relasi pengajuanKrsMahasiswa ada di model Krs yang menunjuk ke PengajuanKrs
                // Atau jika PengajuanKrs punya relasi ke Krs, query bisa dibalik
                // Cara paling mudah:
                // Kita butuh mahasiswa_id dari krs, lalu cek PengajuanKrs untuk mahasiswa_id dan tahun_akademik_id tersebut
                $query->where('tahun_akademik_id', $tahun_akademik_id)
                      ->where('status_pengajuan', 'disetujui_akademik');
            })
            ->orderBy('mahasiswa_id') // Atau berdasarkan NIM mahasiswa
            ->get();
        
        // Alternatif query jika relasi pengajuanKrsMahasiswa tidak ada di Krs:
        // Ambil dulu mahasiswa_id dan semester_mahasiswa dari KRS
        // $krsDataList = Krs::where('mata_kuliah_id', $mata_kuliah_id)
        //     ->where('tahun_akademik_id', $tahun_akademik_id)
        //     ->select('mahasiswa_id', 'semester_mahasiswa')
        //     ->distinct()
        //     ->get();
        //
        // $mahasiswaIdsApproved = PengajuanKrs::whereIn('mahasiswa_id', $krsDataList->pluck('mahasiswa_id'))
        //     ->where('tahun_akademik_id', $tahun_akademik_id)
        //     ->where('status_pengajuan', 'disetujui_akademik')
        //     ->pluck('mahasiswa_id');
        // 
        // $krsEntries = Krs::with(['mahasiswa.user'])
        //     ->where('mata_kuliah_id', $mata_kuliah_id)
        //     ->where('tahun_akademik_id', $tahun_akademik_id)
        //     ->whereIn('mahasiswa_id', $mahasiswaIdsApproved)
        //     ->orderBy('mahasiswa_id')
        //     ->get();


        $mahasiswaDataForGrading = [];
        foreach ($krsEntries as $krs) {
            if ($krs->mahasiswa) {
                $existingKhs = Khs::where('mahasiswa_id', $krs->mahasiswa_id)
                    ->where('mata_kuliah_id', $mata_kuliah_id)
                    ->where('semester', $krs->semester_mahasiswa) // Gunakan semester_mahasiswa dari Krs
                    ->first();

                $mahasiswaDataForGrading[] = [
                    'krs_id' => $krs->id_krs, // Untuk unique key jika perlu
                    'mahasiswa' => $krs->mahasiswa,
                    'semester_mahasiswa' => $krs->semester_mahasiswa, // Ini penting untuk KHS
                    'nilai_existing' => $existingKhs ? $existingKhs->nilai : null,
                ];
            }
        }
        
        // Urutkan berdasarkan NIM mahasiswa
        usort($mahasiswaDataForGrading, function($a, $b) {
            return strcmp($a['mahasiswa']->nim, $b['mahasiswa']->nim);
        });


        $skalaNilai = $this->getSkalaNilai();

        return view('dosen.input_nilai.edit', compact(
            'dosen',
            'jadwalKuliah',
            'mahasiswaDataForGrading',
            'skalaNilai'
        ));
    }

    /**
     * Menyimpan atau memperbarui nilai mahasiswa untuk kelas tertentu.
     */
    public function storeNilai(Request $request, $id_jadwal_kuliah)
    {
        $user = Auth::user();
         if (!$user || !$user->dosen) {
            return redirect()->route('login')->with('error', 'Akses ditolak.');
        }

        $jadwalKuliah = JadwalKuliah::where('dosen_id', $user->dosen->id_dosen)
                                    ->findOrFail($id_jadwal_kuliah);

        $mata_kuliah_id = $jadwalKuliah->mata_kuliah_id;
        $skalaNilai = $this->getSkalaNilai(); // Ambil skala nilai untuk validasi

        // Validasi input: nilai harus berupa array, setiap item memiliki mahasiswa_id, semester_mahasiswa, dan nilai
        $validated = $request->validate([
            'nilai_input' => 'required|array',
            'nilai_input.*.mahasiswa_id' => 'required|exists:mahasiswas,id_mahasiswa',
            'nilai_input.*.semester_mahasiswa' => 'required|integer|min:1',
            'nilai_input.*.nilai' => ['nullable', 'string', 'max:2', Rule::in(array_merge($skalaNilai, ['']))], // '' untuk mengosongkan nilai
        ]);

        DB::beginTransaction();
        try {
            foreach ($validated['nilai_input'] as $input) {
                 Khs::updateOrCreate(
                    [
                        'mahasiswa_id' => $input['mahasiswa_id'],
                        'mata_kuliah_id' => $mata_kuliah_id,
                        'semester' => $input['semester_mahasiswa'],
                    ],
                    [
                        'nilai' => $input['nilai'] ?: null, // Simpan null jika string kosong
                    ]
                );
            }
            DB::commit();
            return redirect()->route('dosen.input-nilai.showKelas', $id_jadwal_kuliah)
                             ->with('success', 'Nilai mahasiswa berhasil disimpan/diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error saving grades by Dosen ID {$user->dosen->id_dosen} for Jadwal ID {$id_jadwal_kuliah}: " . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan saat menyimpan nilai: ' . $e->getMessage());
        }
    }
}