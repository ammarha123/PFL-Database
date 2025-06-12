<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Evaluasi extends Model
{
    protected $table = 'evaluasi';

    protected $fillable = [
        'rapor_id',
        'positif_attacking',
        'negatif_attacking',
        'positif_defending',
        'negatif_defending',
        'positif_transisi',
        'negatif_transisi',
    ];

    public function rapor()
    {
        return $this->belongsTo(RaporPerkembangan::class, 'rapor_id');
    }
}
