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
        'vo2max_level',
        'vo2max_balikan'
       
    ];

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
