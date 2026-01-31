<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EcoLocation extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    // Relasi ke Log
    public function logs()
    {
        return $this->hasMany(EcoStockLog::class, 'location_id');
    }
}