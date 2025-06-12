<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Player extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'bod', 'position', 'photo_profile'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }    

    // ✅ Fix: Correct pivot table reference
    public function datalatihan()
    {
        return $this->belongsToMany(DataLatihan::class, 'datalatihan_player', 'player_id', 'datalatihan_id')
            ->withPivot('status', 'reason')
            ->withTimestamps();
    }

    public function teams()
    {
        return $this->belongsToMany(Team::class, 'player_team', 'player_id', 'team_id');
    }

    public function rapor()
{
    return $this->hasOne(RaporPerkembangan::class);
}
public function raporPerkembangan()
{
    return $this->hasOne(RaporPerkembangan::class, 'player_id')->with(['evaluasi', 'positions', 'targets']);
}


}
