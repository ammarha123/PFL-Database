<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataPertandingan extends Model
{
    use HasFactory;

    protected $table = 'datapertandingan';

    protected $fillable = [
        'tanggal',
        'home_team',
        'away_team',
        'home_score',
        'away_score',
        'location',
        'notes',
        'match_summary'
    ];

    public function goals()
    {
        return $this->hasMany(Goal::class, 'match_id');
    }

    public function yellowCards()
    {
        return $this->hasMany(YellowCard::class, 'match_id');
    }

    public function redCards()
    {
        return $this->hasMany(RedCard::class, 'match_id');
    }

    public function starting11()
    {
        return $this->hasMany(Starting11::class, 'match_id');
    }

    public function videos()
    {
        return $this->hasMany(DataVideo::class, 'match_id');
    }


}

