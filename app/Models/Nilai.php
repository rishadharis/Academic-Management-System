<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nilai extends Model
{
    use HasFactory;
    protected $table = 'nilais';
    protected $fillable = ['users_id', 'kelas_id', 'nilai_uts', 'nilai_uas'];

    public function user()
    {
        return $this->belongsTo(User::class, 'users_id', 'id');
    }

    public function kelas()
    {
        return $this->belongsTo(kelas::class, 'kelas_id', 'id');
    }
}
