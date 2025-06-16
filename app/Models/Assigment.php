<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assigment extends Model
{
    use HasFactory;
    protected $table = 'assigments';
    protected $fillable = ['kelas_id', 'name', 'desc', 'deadline'];

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id', 'id');
    }

    public function assigment_upload()
    {
        return $this->hasMany(AssigmentUpload::class, 'assigments_id', 'id');
    }
}
