<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Goal extends Model
{
    use HasFactory;

    protected $table = 'goal';
    protected $fillable = ['match_id', 'player', 'minute'];

    public function match()
    {
        return $this->belongsTo(DataPertandingan::class, 'match_id');
    }
}

