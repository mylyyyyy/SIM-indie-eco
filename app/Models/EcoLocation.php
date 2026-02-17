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
    public function stockLogs()
    {
        // EcoLocation (Lokasi) memiliki banyak (hasMany) EcoStockLog (Riwayat)
        // 'location_id' adalah foreign key yang ada di tabel eco_stock_logs
        return $this->hasMany(EcoStockLog::class, 'location_id');
    }
}