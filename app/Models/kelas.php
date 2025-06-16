<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class kelas extends Model
{
    use HasFactory;
    protected $table = 'kelas';
    protected $guarded = [];

    public function akademik()
    {
        return $this->belongsTo(TahunAkademik::class, 'tahun_akademiks_id', 'id');
    }

    public function matkul()
    {
        return $this->belongsTo(Matkul::class, 'matkuls_id', 'id');
    }

    public function dosen()
    {
        return $this->hasMany(KelasDosen::class, 'kelas_id', 'id');
    }

    public function mahasiswa()
    {
        return $this->hasMany(KelasMahasiswa::class, 'kelas_id', 'id');
    }

    public function jadwal()
    {
        return $this->hasMany(Jadwal::class, 'kelas_id', 'id');
    }

    public function assigment()
    {
        return $this->hasMany(Assigment::class, 'kelas_id', 'id');
    }
}
