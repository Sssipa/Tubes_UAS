<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ruangan extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_ruangan';
    protected $fillable = ['kode', 'nama', 'kapasitas'];

    public function jadwalKuliahs()
    {
        return $this->hasMany(JadwalKuliah::class);
    }
}
