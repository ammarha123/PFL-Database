<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RaporPerkembangan extends Model
{
    protected $table = 'rapor_perkembangan';

    protected $fillable = [
        'player_id',
        'deskripsi_umum',
    ];

    public function player()
    {
        return $this->belongsTo(Player::class);
    }

    public function positions()
    {
        return $this->hasMany(RaporPosition::class, 'rapor_id');
    }
}
