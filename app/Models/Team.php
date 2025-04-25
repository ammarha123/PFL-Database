<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Team extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function players()
    {
        return $this->belongsToMany(Player::class, 'player_team', 'team_id', 'player_id');
    }

    // ✅ Fix: Correct pivot table for training sessions
    public function latihanSessions()
    {
        return $this->belongsToMany(DataLatihan::class, 'datalatihan_team', 'team_id', 'datalatihan_id');
    }
}


