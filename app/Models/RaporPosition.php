<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RaporPosition extends Model
{
    protected $fillable = ['rapor_id', 'position_code'];

    public function rapor()
    {
        return $this->belongsTo(RaporPerkembangan::class, 'rapor_id');
    }
}

