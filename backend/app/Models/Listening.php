<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Listening extends Model
{
    use HasFactory;

    protected $table = 'listening';

    protected $fillable = ['audio_url', 'transcript', 'title'];

    public function questions()
    {
        return $this->hasMany(ListeningQuestion::class);
    }
}
