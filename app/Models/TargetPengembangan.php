<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TargetPengembangan extends Model
{
    protected $table = 'target_pengembangan';

    protected $fillable = ['rapor_id', 'target', 'kapan_tercapai', 'cara_mencapai'];
    public function rapor()
    {
        return $this->belongsTo(RaporPerkembangan::class, 'rapor_id');
    }
}
