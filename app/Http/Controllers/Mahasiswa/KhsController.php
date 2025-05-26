<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Khs;
use App\Models\Mahasiswa;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; 

class KhsController extends Controller
{
     // Definisikan skala bobot nilai Anda di sini atau di tempat lain yang lebih global
    protected function getBobotNilai($nilaiHuruf)
    {
        $nilaiHuruf = strtoupper(trim($nilaiHuruf ?? ''));
        $map = [
            'A+'  => 4.0,
            'A' => 3.5,
            'A-'  => 3.0,
            'B+' => 2.5,
            'B'  => 2.0,
            'B-' => 1.5,
            'C'  => 1.0,
            'D'  => 0.5,
            'E'  => 0.0,
            'T'  => null, 
        ];
        return $map[$nilaiHuruf] ?? null; // Return null jika nilai tidak valid atau tidak dihitung
    }

    /**
     * Menampilkan ringkasan KHS (daftar semester dengan IPS dan IPK).
     */
    public function index()
    {
        $user = Auth::user();
        if (!$user || !$user->mahasiswa) {
            return redirect()->route('login')->with('error', 'Data mahasiswa tidak ditemukan.');
        }
        $mahasiswa = $user->mahasiswa;

        // Ambil semua entri KHS mahasiswa dengan mata kuliahnya untuk perhitungan
        $semuaKhsMahasiswa = Khs::where('mahasiswa_id', $mahasiswa->id_mahasiswa)
            ->with('mataKuliah') // Eager load mata kuliah untuk mendapatkan SKS
            ->orderBy('semester', 'asc')
            ->get();

        $hasilPerSemester = [];
        $totalSksKumulatif = 0;
        $totalMutuKumulatif = 0;

        // Kelompokkan KHS berdasarkan semester
        $khsGroupedBySemester = $semuaKhsMahasiswa->groupBy('semester');

        foreach ($khsGroupedBySemester as $semester => $khsDalamSemester) {
            $totalSksSemesterIni = 0;
            $totalMutuSemesterIni = 0;

            foreach ($khsDalamSemester as $khs) {
                if ($khs->mataKuliah) { // Pastikan mata kuliah ada
                    $bobot = $this->getBobotNilai($khs->nilai);
                    if ($bobot !== null) { // Hanya hitung jika ada bobot valid
                        $sks = $khs->mataKuliah->sks;
                        $totalSksSemesterIni += $sks;
                        $totalMutuSemesterIni += ($sks * $bobot);
                    }
                }
            }

            $ips = ($totalSksSemesterIni > 0) ? round($totalMutuSemesterIni / $totalSksSemesterIni, 2) : 0;
            $hasilPerSemester[$semester] = [
                'semester' => $semester,
                'total_sks' => $totalSksSemesterIni,
                'total_mutu' => $totalMutuSemesterIni,
                'ips' => $ips,
            ];

            $totalSksKumulatif += $totalSksSemesterIni;
            $totalMutuKumulatif += $totalMutuSemesterIni;
        }

        $ipk = ($totalSksKumulatif > 0) ? round($totalMutuKumulatif / $totalSksKumulatif, 2) : 0;

        return view('mahasiswa.khs.index', compact(
            'mahasiswa',
            'hasilPerSemester',
            'totalSksKumulatif',
            'ipk'
        ));
    }

    /**
     * Menampilkan detail KHS untuk semester tertentu.
     */
    public function show($semester)
    {
        $user = Auth::user();
        if (!$user || !$user->mahasiswa) {
            return redirect()->route('login')->with('error', 'Data mahasiswa tidak ditemukan.');
        }
        $mahasiswa = $user->mahasiswa;

        $khsSemesterIni = Khs::where('mahasiswa_id', $mahasiswa->id_mahasiswa)
            ->where('semester', $semester)
            ->with('mataKuliah')
            ->orderBy('mata_kuliah_id') // Atau berdasarkan kode mata kuliah
            ->get();

        if ($khsSemesterIni->isEmpty()) {
            // Bisa juga redirect ke index dengan pesan error jika semester tidak valid
            return redirect()->route('mahasiswa.khs.index')->with('error', 'Data KHS untuk semester ' . $semester . ' tidak ditemukan.');
        }

        $detailNilaiSemester = [];
        $totalSksSemesterIni = 0;
        $totalMutuSemesterIni = 0;

        foreach ($khsSemesterIni as $khs) {
            if ($khs->mataKuliah) {
                $bobot = $this->getBobotNilai($khs->nilai);
                $mutu = ($bobot !== null) ? ($khs->mataKuliah->sks * $bobot) : null;

                $detailNilaiSemester[] = [
                    'kode_mk' => $khs->mataKuliah->kode,
                    'nama_mk' => $khs->mataKuliah->nama,
                    'sks' => $khs->mataKuliah->sks,
                    'nilai_huruf' => $khs->nilai,
                    'bobot' => $bobot,
                    'mutu' => $mutu,
                ];

                if ($bobot !== null) {
                    $totalSksSemesterIni += $khs->mataKuliah->sks;
                    $totalMutuSemesterIni += $mutu;
                }
            }
        }

        $ips = ($totalSksSemesterIni > 0) ? round($totalMutuSemesterIni / $totalSksSemesterIni, 2) : 0;

        return view('mahasiswa.khs.show', compact(
            'mahasiswa',
            'semester',
            'detailNilaiSemester',
            'totalSksSemesterIni',
            'totalMutuSemesterIni',
            'ips'
        ));
    }
}
