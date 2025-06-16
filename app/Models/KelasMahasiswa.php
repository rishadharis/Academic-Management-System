<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KelasMahasiswa extends Model
{
    use HasFactory;
    protected $table = 'kelas_mahasiswas';
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class, 'users_id', 'id');
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id', 'id');
    }
}
