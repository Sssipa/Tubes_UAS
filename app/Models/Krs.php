<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Krs extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_krs';
    protected $fillable = ['mahasiswa_id', 'mata_kuliah_id', 'tahun_akademik_id', 'semester_mahasiswa'];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class);
    }

    public function mataKuliah()
    {
        return $this->belongsTo(MataKuliah::class);
    }

    public function pengajuanKrsMahasiswa()
    {
        return $this->belongsTo(PengajuanKrs::class, 'mahasiswa_id', $this->mahasiswa_id)
                    ->where('tahun_akademik_id', $this->tahun_akademik_id);
    }
}
