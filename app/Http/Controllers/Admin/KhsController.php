<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Khs;
use App\Models\Krs;
use App\Models\Mahasiswa;
use App\Models\TahunAkademik;
use App\Models\MataKuliah; // Untuk validasi
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class KhsController extends Controller
{
    /**
     * Menampilkan form untuk memilih mahasiswa, tahun akademik, dan semester
     * untuk mengelola KHS.
     */
    public function index(Request $request)
    {
        $mahasiswas = Mahasiswa::orderBy('nama')->get();
        $tahunAkademiks = TahunAkademik::orderBy('tahun', 'desc')->orderBy('semester', 'desc')->get();

        $selectedMahasiswa = null;
        $selectedTahunAkademik = null;
        $inputSemesterMahasiswa = null;
        $krsDetails = collect(); // Menggunakan koleksi kosong sebagai default
        $khsDetails = collect(); // Menggunakan koleksi kosong sebagai default

        if ($request->filled('mahasiswa_id') && $request->filled('tahun_akademik_id') && $request->filled('semester_mahasiswa')) {
            $selectedMahasiswa = Mahasiswa::findOrFail($request->mahasiswa_id);
            $selectedTahunAkademik = TahunAkademik::findOrFail($request->tahun_akademik_id);
            $inputSemesterMahasiswa = $request->semester_mahasiswa;

            // Ambil data KRS mahasiswa untuk tahun akademik dan semester mahasiswa yang dipilih
            $krsDetails = Krs::with(['mataKuliah'])
                ->where('mahasiswa_id', $selectedMahasiswa->id_mahasiswa)
                ->where('tahun_akademik_id', $selectedTahunAkademik->id_tahun_akademik)
                ->where('semester_mahasiswa', $inputSemesterMahasiswa)
                ->get();

            // Ambil data KHS yang sudah ada untuk ditampilkan
            if ($krsDetails->isNotEmpty()) {
                $mataKuliahIds = $krsDetails->pluck('mata_kuliah_id')->toArray();
                $khsDetails = Khs::where('mahasiswa_id', $selectedMahasiswa->id_mahasiswa)
                                ->whereIn('mata_kuliah_id', $mataKuliahIds)
                                 ->where('semester', $inputSemesterMahasiswa) // Pastikan semester KHS sesuai
                                ->get()
                                 ->keyBy('mata_kuliah_id'); // Key by mata_kuliah_id untuk akses mudah di view
            }
        }

        return view('admin.khs.index', compact(
            'mahasiswas',
            'tahunAkademiks',
            'selectedMahasiswa',
            'selectedTahunAkademik',
            'inputSemesterMahasiswa',
            'krsDetails',
            'khsDetails'
        ));
    }

    /**
     * Menyimpan atau memperbarui data KHS.
     */
    public function storeOrUpdate(Request $request)
    {
        $request->validate([
            'mahasiswa_id' => 'required|exists:mahasiswas,id_mahasiswa',
            'tahun_akademik_id' => 'required|exists:tahun_akademiks,id_tahun_akademik',
            'semester_mahasiswa' => 'required|integer|min:1',
            'nilai.*.mata_kuliah_id' => 'required|exists:mata_kuliahs,id_mata_kuliah',
            'nilai.*.nilai' => ['nullable', 'string', 'max:2', Rule::in(['A', 'A-', 'B+', 'B', 'B-', 'C', 'D', 'E'])], // Sesuaikan opsi nilai
        ]);

        $mahasiswaId = $request->mahasiswa_id;
        $semesterMahasiswa = $request->semester_mahasiswa;
        $nilaiInput = $request->input('nilai', []);

        DB::beginTransaction();
        try {
            foreach ($nilaiInput as $mataKuliahId => $data) {
                if (isset($data['nilai'])) { // Hanya proses jika ada input nilai
                    Khs::updateOrCreate(
                        [
                            'mahasiswa_id' => $mahasiswaId,
                            'mata_kuliah_id' => $mataKuliahId,
                            'semester' => $semesterMahasiswa,
                        ],
                        [
                            'nilai' => $data['nilai'] ?: null, // Simpan null jika string kosong
                            // 'bobot_nilai' => $this->calculateBobot($data['nilai']), // Opsional: jika ada kolom bobot
                            // 'created_at' & 'updated_at' akan dihandle otomatis
                        ]
                    );
                }
            }
            DB::commit();
            return redirect()->back()->with('success', 'Data KHS berhasil disimpan/diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            // Log::error('Error saving KHS: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan KHS: ' . $e->getMessage());
        }
    }

    // Opsional: Fungsi untuk menghitung bobot nilai jika diperlukan
    // private function calculateBobot($nilaiHuruf)
    // {
    //     $bobotMap = ['A' => 4.0, 'AB' => 3.5, 'B' => 3.0, 'BC' => 2.5, 'C' => 2.0, 'CD' => 1.5, 'D' => 1.0, 'E' => 0.0, 'K' => null, 'T' => null];
    //     return $bobotMap[$nilaiHuruf] ?? null;
    // }
}
