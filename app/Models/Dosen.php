<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dosen extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_dosen';
    protected $fillable = ['nidn', 'nama', 'alamat', 'email', 'telepon'];

    public function mataKuliahs()
    {
        return $this->hasMany(MataKuliah::class, 'dosen_id', 'id_dosen');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'dosen_id', 'id_dosen');
    }

    
    public function jadwalKuliahs()
    {
        return $this->hasMany(JadwalKuliah::class, 'dosen_id', 'id_dosen');
    }

}
