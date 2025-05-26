<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TahunAkademik extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_tahun_akademik';
    protected $fillable = ['tahun', 'semester', 'status_aktif'];

    public function mahasiswas()
    {
        return $this->hasMany(Mahasiswa::class);
    }
        public function jadwalKuliahs()
    {
        return $this->hasMany(JadwalKuliah::class);
    }
    public function krs() 
    {
        return $this->hasMany(Krs::class, 'tahun_akademik_id', 'id_tahun_akademik');
    }
}
