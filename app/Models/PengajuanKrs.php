<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengajuanKrs extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang terhubung dengan model.
     *
     * @var string
     */
    protected $table = 'pengajuan_krs';

    /**
     * Atribut yang dapat diisi secara massal.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'mahasiswa_id',
        'tahun_akademik_id',
        'total_sks_diambil',
        'status_pengajuan',
        'catatan_akademik',
        'tanggal_pengajuan_mahasiswa',
        'tanggal_keputusan_akademik',
        'admin_id_keputusan',
    ];

    /**
     * Atribut yang harus di-cast ke tipe data tertentu.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'tanggal_pengajuan_mahasiswa' => 'datetime',
        'tanggal_keputusan_akademik' => 'datetime',
        'total_sks_diambil' => 'integer',
    ];

    /**
     * Mendapatkan data mahasiswa yang terkait dengan pengajuan KRS ini.
     */
    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'mahasiswa_id', 'id_mahasiswa');
    }

    /**
     * Mendapatkan data tahun akademik yang terkait dengan pengajuan KRS ini.
     */
    public function tahunAkademik()
    {
        return $this->belongsTo(TahunAkademik::class, 'tahun_akademik_id', 'id_tahun_akademik');
    }

    /**
     * Mendapatkan data admin (user) yang mengambil keputusan untuk pengajuan KRS ini.
     */
    public function adminPengambilKeputusan()
    {
        // Asumsi primary key di tabel users adalah 'id_user' sesuai migrasi Anda
        return $this->belongsTo(User::class, 'admin_id_keputusan', 'id_user');
    }

    /**
     * Mendapatkan detail mata kuliah (dari tabel Krs) yang terkait dengan pengajuan ini.
     * Relasi ini didasarkan pada mahasiswa_id dan tahun_akademik_id yang sama.
     */
    public function detailKrs()
    {
        // Asumsi tabel 'krs' memiliki kolom 'mahasiswa_id' dan 'tahun_akademik_id'
        // dan 'semester_mahasiswa' untuk mencocokkan dengan semester pengajuan jika diperlukan.
        // Untuk saat ini, kita cocokkan berdasarkan mahasiswa dan tahun akademik saja.
        return $this->hasMany(Krs::class, 'mahasiswa_id', 'mahasiswa_id')
                    ->where('tahun_akademik_id', $this->tahun_akademik_id);
        
        // Jika Anda juga menyimpan 'semester_mahasiswa' di 'pengajuan_krs' dan ingin mencocokkannya:
        // (Ini memerlukan penambahan kolom 'semester_mahasiswa' di tabel 'pengajuan_krs' jika belum ada)
        // return $this->hasMany(Krs::class, 'mahasiswa_id', 'mahasiswa_id')
        //             ->where('tahun_akademik_id', $this->tahun_akademik_id)
        //             ->where('semester_mahasiswa', $this->semester_mahasiswa); // Asumsi ada $this->semester_mahasiswa
    }
}
