<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Annoncement extends Model
{
    use HasFactory;
    protected $table = 'annoncements';
    protected $fillable = ['users_id', 'desc'];

    public function user()
    {
        return $this->belongsTo(User::class, 'users_id', 'id');
    }
}
