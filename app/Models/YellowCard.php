<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class YellowCard extends Model
{
    use HasFactory;

    protected $fillable = ['match_id', 'player', 'minute'];

    public function match()
    {
        return $this->belongsTo(DataPertandingan::class, 'match_id');
    }
}

