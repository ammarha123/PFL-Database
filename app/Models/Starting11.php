<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Starting11 extends Model
{
    use HasFactory;

    protected $table = 'starting_11'; // ✅ Correct Table Name

    protected $fillable = ['match_id', 'player_name', 'position', 'x', 'y'];

    // Relationship: Each starting 11 player belongs to a match
    public function match()
    {
        return $this->belongsTo(DataPertandingan::class, 'match_id');
    }

    // Relationship: Link to Player
    public function player()
    {
        return $this->belongsTo(Player::class, 'player_id');
    }
}
