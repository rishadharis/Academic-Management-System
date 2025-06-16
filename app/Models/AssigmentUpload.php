<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssigmentUpload extends Model
{
    use HasFactory;
    protected $table = 'assigment_uploads';
    protected $fillable = ['users_id', 'assigments_id', 'file', 'status', 'nilai'];

    public function mahasiswa()
    {
        return $this->belongsTo(User::class, 'users_id', 'id');
    }
}
