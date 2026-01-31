<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EcoStockLog extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    // Relasi ke Lokasi
    public function location()
    {
        return $this->belongsTo(EcoLocation::class, 'location_id');
    }
}