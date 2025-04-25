<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvaluasiMandiri extends Model
{
    use HasFactory;

    protected $table = 'evaluasi_mandiri';

    protected $fillable = [
        'user_id',
        'tanggal',
        'positif_attacking',
        'negatif_attacking',
        'positif_defending',
        'negatif_defending',
        'target_pengembangan_1',
        'target_pengembangan_2',
        'target_pengembangan_3',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
