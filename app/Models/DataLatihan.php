<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataLatihan extends Model
{
    use HasFactory;

    protected $table = 'datalatihan';

    protected $fillable = ['tanggal', 'latihan_file_path'];

    // ✅ Correct pivot table for teams
    public function teams()
    {
        return $this->belongsToMany(Team::class, 'datalatihan_team', 'datalatihan_id', 'team_id')->withTimestamps();
    }

    // ✅ Correct pivot table for players
    public function players()
    {
        return $this->belongsToMany(Player::class, 'datalatihan_player', 'datalatihan_id', 'player_id')
            ->withPivot('status', 'reason')
            ->withTimestamps();
    }

    public function videos()
    {
        return $this->hasMany(DataVideo::class, 'latihan_id');
    }

}

