<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_mahasiswa';
    protected $fillable = ['nim', 'nama', 'alamat', 'jenis_kelamin', 'email', 'telepon', 'tahun_akademik_id'];

    public function profil()
    {
        return $this->hasOne(Profil::class, 'mahasiswa_id', 'id_mahasiswa');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'mahasiswa_id', 'id_mahasiswa');
    }

    public function tahunAkademik()
    {
        return $this->belongsTo(TahunAkademik::class, 'tahun_akademik_id', 'id_tahun_akademik');
    }

    public function krs()
    {
        // Asumsi KRS diisi per tahun akademik aktif, bukan per semester mahasiswa
        return $this->hasMany(Krs::class, 'mahasiswa_id', 'id_mahasiswa');
    }

    public function khs()
    {
        return $this->hasMany(Khs::class, 'mahasiswa_id', 'id_mahasiswa');
    }
    public function hitungSemesterBerjalan(TahunAkademik $tahunAkademikSaatIni)
    {
        $tahunMasukMahasiswa = $this->tahunAkademik; 

        if (!$tahunMasukMahasiswa || !$tahunAkademikSaatIni) {
            return 1; // Default jika data tidak lengkap
        }

        // Asumsi format tahun "YYYY/YYYY"
        $startYearMasuk = (int)substr($tahunMasukMahasiswa->tahun, 0, 4);
        $startYearSaatIni = (int)substr($tahunAkademikSaatIni->tahun, 0, 4);

        $selisihTahun = $startYearSaatIni - $startYearMasuk;
        $semester = ($selisihTahun * 2) + 1; // Mulai dari semester 1

        // Penyesuaian berdasarkan semester Ganjil/Genap
        if ($tahunMasukMahasiswa->semester == 'Genap') {
            // Jika masuk di semester Genap, tahun pertama hanya 1 semester
            // Tapi saat ini sudah dihitung sebagai +1 dari selisih tahun.
            // Jika tahun sama, dan semester saat ini Ganjil, berarti belum mulai semester.
            // Ini perlu logika yang lebih hati-hati.
            // Untuk sederhana:
            // Jika semester masuk Genap, dan semester saat ini Ganjil di tahun yang sama, itu aneh.
            // Jika semester masuk Ganjil, semester saat ini Genap di tahun yang sama -> semester 2
            // Jika semester masuk Ganjil, semester saat ini Ganjil di tahun berikutnya -> semester 3
        }
        if ($tahunAkademikSaatIni->semester == 'Genap') {
            $semester++;
        }
        // Jika mahasiswa masuk semester Genap dan TA saat ini Ganjil di tahun yang sama,
        // maka semester berjalan seharusnya 0 atau belum ada.
        // Logika ini perlu disempurnakan sesuai aturan kampus.
        // Untuk sementara, kita pakai logika sederhana, bisa jadi kurang akurat.
        // Misal:
        $semesterOffsetMasuk = ($tahunMasukMahasiswa->semester == 'Ganjil') ? 0 : 1;
        $semesterOffsetSaatIni = ($tahunAkademikSaatIni->semester == 'Ganjil') ? 0 : 1;
        
        $totalSemesterDilalui = (($startYearSaatIni - $startYearMasuk) * 2) + ($semesterOffsetSaatIni - $semesterOffsetMasuk) + 1;
        
        return max(1, $totalSemesterDilalui); // Minimal semester 1
    }
}
