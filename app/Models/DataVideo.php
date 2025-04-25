<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataVideo extends Model
{
    use HasFactory;

    protected $table = 'datavideos';

    protected $fillable = [
        'video_category',
        'link_video',
        'description',
        'match_id',
        'latihan_id',
    ];

    // Relationships
    public function match()
    {
        return $this->belongsTo(DataPertandingan::class, 'match_id');
    }

    public function latihan()
    {
        return $this->belongsTo(DataLatihan::class, 'latihan_id');
    }
}
