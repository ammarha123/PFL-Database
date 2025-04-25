<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataTes extends Model
{
    use HasFactory;

    protected $table = 'datates';

    protected $fillable = [
        'player_id',
        'team_id',
        'category',
        'tanggal',
        'weight',
        'height',
        'bmi',
        'fat_chest',
        'fat_thighs',
        'fat_abs',
        'fat_percentage',
        'fms_squat',
        'fms_hurdle',
        'fms_lunge',
        'fms_shoulder',
        'fms_leg_raise',
        'fms_push_up',
        'fms_rotary',
        'fms_total',
        'vo2max_type',
        'vo2max_duration',
        'speed',
        'oxygen',
        'vo2max_score',
        'mas_type',
        'mas_speed',
        'mas_duration',
        'mas_distance',
    ];

    // Relationship: Each test record belongs to a player
    public function player()
    {
        return $this->belongsTo(Player::class, 'player_id'); 
    }    

    // Relationship: Each test record may belong to a team (for group tests)
    public function team()
    {
        return $this->belongsTo(Team::class, 'team_id');
    }
}
