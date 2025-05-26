<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profil extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_profil';
    protected $fillable = ['mahasiswa_id', 'alamat', 'tempat_lahir', 'tanggal_lahir', 'foto'];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class);
    }
}
