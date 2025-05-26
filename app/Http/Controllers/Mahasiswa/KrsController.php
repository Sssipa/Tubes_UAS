<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Krs;
use App\Models\PengajuanKrs;
use App\Models\Mahasiswa;
use App\Models\TahunAkademik;
use App\Models\JadwalKuliah;
use App\Models\MataKuliah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class KrsController extends Controller
{
    /**
     * Menampilkan halaman KRS untuk mahasiswa.
     * Ini akan menampilkan form pengisian atau status KRS yang sudah ada.
     */
    public function createOrShow()
    {
        $user = Auth::user();
        if (!$user || !$user->mahasiswa) {
            return redirect()->route('auth.login')->with('error', 'Data mahasiswa tidak ditemukan. Silakan login kembali.');
        }
        $mahasiswa = $user->mahasiswa;

        $tahunAkademikAktif = TahunAkademik::where('status_aktif', true)->first();
        if (!$tahunAkademikAktif) {
            return view('mahasiswa.krs.index', [
                'mahasiswa' => $mahasiswa,
                'tahunAkademikAktif' => null,
                'pesanError' => 'Saat ini tidak ada periode pengisian KRS yang aktif.'
            ]);
        }

        // Cek pengajuan KRS yang sudah ada untuk mahasiswa dan tahun akademik aktif
        $pengajuanKrs = PengajuanKrs::where('mahasiswa_id', $mahasiswa->id_mahasiswa)
            ->where('tahun_akademik_id', $tahunAkademikAktif->id_tahun_akademik)
            ->first();

        $jadwalKuliahDitawarkan = collect();
        $krsMahasiswaSaatIniIds = [];
        $sksDiambil = 0;
        $batasSks = 24; // Bisa dibuat dinamis nantinya

        // Tentukan semester mahasiswa saat ini (perlu logika yang lebih baik, misal dari data mahasiswa)
        // Untuk contoh, kita ambil dari tahun akademik (jika ganjil = semester ganjil, genap = semester genap dari tahun masuk)
        // Ini perlu disesuaikan dengan bagaimana Anda menentukan semester berjalan mahasiswa
        $semesterMahasiswaBerjalan = $mahasiswa->hitungSemesterBerjalan($tahunAkademikAktif);


        // Jika belum ada pengajuan atau statusnya memungkinkan untuk diubah (misal 'ditolak_akademik')
        $bisaMengisiKrs = !$pengajuanKrs || in_array($pengajuanKrs->status_pengajuan, ['ditolak_akademik']);

        if ($bisaMengisiKrs) {
            $jadwalKuliahDitawarkan = JadwalKuliah::with(['mataKuliah.dosen', 'ruangan'])
                ->where('tahun_akademik_id', $tahunAkademikAktif->id_tahun_akademik)
                // Tambahkan filter lain jika perlu (misal berdasarkan semester mata kuliah yang cocok untuk mahasiswa)
                ->orderBy('id_jadwal_kuliah') // atau order by nama mata kuliah
                ->get();

            // Ambil KRS yang sudah dipilih sebelumnya jika ada (misal setelah ditolak dan ingin revisi)
            if ($pengajuanKrs) { // Jika ada pengajuan sebelumnya (misal ditolak)
                $krsDetailSebelumnya = Krs::where('mahasiswa_id', $mahasiswa->id_mahasiswa)
                                        ->where('tahun_akademik_id', $tahunAkademikAktif->id_tahun_akademik)
                                        ->where('semester_mahasiswa', $semesterMahasiswaBerjalan)
                                        ->pluck('mata_kuliah_id')->toArray();
                $krsMahasiswaSaatIniIds = $krsDetailSebelumnya;
                if(!empty($krsMahasiswaSaatIniIds)){
                    $sksDiambil = MataKuliah::whereIn('id_mata_kuliah', $krsMahasiswaSaatIniIds)->sum('sks');
                }
            }
        } else { // Jika sudah diajukan dan menunggu atau sudah disetujui
            // Ambil detail KRS yang sudah diajukan/disetujui
            $krsDetailDiajukan = Krs::with(['mataKuliah.dosen'])
                ->where('mahasiswa_id', $mahasiswa->id_mahasiswa)
                ->where('tahun_akademik_id', $tahunAkademikAktif->id_tahun_akademik)
                ->where('semester_mahasiswa', $semesterMahasiswaBerjalan)
                ->get();
            // Kirim ini ke view untuk ditampilkan sebagai read-only
            // $jadwalKuliahDitawarkan akan berisi KRS yang sudah diambil
            $jadwalKuliahDitawarkan = $krsDetailDiajukan; // Untuk ditampilkan di tabel
            if(!empty($krsDetailDiajukan)){
                $sksDiambil = $krsDetailDiajukan->sum(function($krs) {
                    return $krs->mataKuliah->sks ?? 0;
                });
            }
        }


        return view('mahasiswa.krs.index', compact(
            'mahasiswa',
            'tahunAkademikAktif',
            'pengajuanKrs',
            'jadwalKuliahDitawarkan',
            'krsMahasiswaSaatIniIds',
            'sksDiambil',
            'batasSks',
            'bisaMengisiKrs',
            'semesterMahasiswaBerjalan'
        ));
    }

    /**
     * Menyimpan pengajuan KRS mahasiswa.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        if (!$user || !$user->mahasiswa) {
            return redirect()->route('auth.login')->with('error', 'Sesi Anda telah berakhir.');
        }
        $mahasiswa = $user->mahasiswa;

        $request->validate([
            'tahun_akademik_id' => 'required|exists:tahun_akademiks,id_tahun_akademik',
            'semester_mahasiswa_berjalan' => 'required|integer|min:1',
            'mata_kuliah_ids' => 'nullable|array',
            'mata_kuliah_ids.*' => 'exists:mata_kuliahs,id_mata_kuliah',
        ]);

        $tahunAkademikAktif = TahunAkademik::find($request->tahun_akademik_id);
        if (!$tahunAkademikAktif || !$tahunAkademikAktif->status_aktif) {
            return redirect()->route('mahasiswa.krs.createOrShow')->with('error', 'Periode pengisian KRS sudah ditutup atau tidak valid.');
        }

        // Cek apakah mahasiswa boleh mengajukan/mengupdate
        $pengajuanKrsSebelumnya = PengajuanKrs::where('mahasiswa_id', $mahasiswa->id_mahasiswa)
                                    ->where('tahun_akademik_id', $tahunAkademikAktif->id_tahun_akademik)
                                    ->first();

        if ($pengajuanKrsSebelumnya && !in_array($pengajuanKrsSebelumnya->status_pengajuan, ['ditolak_akademik', 'diajukan_mahasiswa'])) {
            // Jika statusnya bukan ditolak atau masih diajukan (mungkin mahasiswa ingin update sebelum diproses admin)
            // Jika sudah disetujui, tidak boleh diubah lagi dari sini.
            if($pengajuanKrsSebelumnya->status_pengajuan == 'disetujui_akademik'){
                return redirect()->route('mahasiswa.krs.createOrShow')->with('error', 'KRS Anda sudah disetujui dan tidak dapat diubah lagi.');
            }
        }


        $selectedMataKuliahIds = $request->input('mata_kuliah_ids', []);
        $semesterMahasiswaBerjalan = $request->semester_mahasiswa_berjalan;

        $totalSksDipilih = 0;
        if (!empty($selectedMataKuliahIds)) {
            $totalSksDipilih = MataKuliah::whereIn('id_mata_kuliah', $selectedMataKuliahIds)->sum('sks');
        }

        $batasSks = 24; // Ambil dari aturan
        if ($totalSksDipilih > $batasSks) {
            return redirect()->route('mahasiswa.krs.createOrShow')
                            ->withInput()
                            ->with('error', "Total SKS yang dipilih ($totalSksDipilih SKS) melebihi batas maksimal ($batasSks SKS).");
        }
        if (empty($selectedMataKuliahIds) && $totalSksDipilih == 0) {
            return redirect()->route('mahasiswa.krs.createOrShow')
                            ->withInput()
                            ->with('error', "Anda belum memilih mata kuliah apapun.");
        }


        DB::beginTransaction();
        try {
            // 1. Hapus detail KRS lama untuk mahasiswa, tahun akademik, dan semester mahasiswa ini
            Krs::where('mahasiswa_id', $mahasiswa->id_mahasiswa)
                ->where('tahun_akademik_id', $tahunAkademikAktif->id_tahun_akademik)
                ->where('semester_mahasiswa', $semesterMahasiswaBerjalan) // penting untuk membedakan krs semester lalu
                ->delete();

            // 2. Simpan detail KRS baru
            if (!empty($selectedMataKuliahIds)) {
                $krsDataToInsert = [];
                foreach ($selectedMataKuliahIds as $mataKuliahId) {
                    $krsDataToInsert[] = [
                        'mahasiswa_id' => $mahasiswa->id_mahasiswa,
                        'mata_kuliah_id' => $mataKuliahId,
                        'tahun_akademik_id' => $tahunAkademikAktif->id_tahun_akademik,
                        'semester_mahasiswa' => $semesterMahasiswaBerjalan,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
                Krs::insert($krsDataToInsert);
            }

            // 3. Buat atau Update PengajuanKrs
            PengajuanKrs::updateOrCreate(
                [
                    'mahasiswa_id' => $mahasiswa->id_mahasiswa,
                    'tahun_akademik_id' => $tahunAkademikAktif->id_tahun_akademik,
                ],
                [
                    'total_sks_diambil' => $totalSksDipilih,
                    'status_pengajuan' => 'diajukan_mahasiswa', // Status kembali ke diajukan
                    'tanggal_pengajuan_mahasiswa' => now(),
                    'catatan_akademik' => null, // Kosongkan catatan admin jika ada pengajuan ulang
                    'tanggal_keputusan_akademik' => null,
                    'admin_id_keputusan' => null,
                ]
            );

            DB::commit();
            return redirect()->route('mahasiswa.krs.createOrShow')->with('success', 'KRS berhasil diajukan/diperbarui.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error saving KRS for mahasiswa ID ' . $mahasiswa->id_mahasiswa . ': ' . $e->getMessage());
            return redirect()->route('mahasiswa.krs.createOrShow')
                            ->withInput()
                            ->with('error', 'Terjadi kesalahan saat menyimpan KRS. Silakan coba lagi.');
        }
    }
}

