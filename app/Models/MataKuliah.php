<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MataKuliah extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_mata_kuliah';
    protected $fillable = ['kode', 'nama', 'sks', 'dosen_id'];

    public function dosen()
    {
        return $this->belongsTo(Dosen::class, 'dosen_id', 'id_dosen');
    }

    public function khs()
    {
        return $this->hasMany(Khs::class, 'mata_kuliah_id', 'id_mata_kuliah');
    }

    public function krs()
    {
        return $this->hasMany(Krs::class, 'mata_kuliah_id', 'id_mata_kuliah');
    }

    public function jadwalKuliah()
    {
        return $this->hasMany(JadwalKuliah::class, 'mata_kuliah_id', 'id_mata_kuliah');
    }
}
