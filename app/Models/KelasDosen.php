<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KelasDosen extends Model
{
    use HasFactory;
    protected $table = 'kelas_dosens';
    protected $guarded = [];

    public function kelas()
    {
        return $this->belongsTo(kelas::class, 'kelas_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'users_id', 'id');
    }
}
