<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EcoIncome extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nama_cabang',
        'hari',
        'tanggal',
        'nama_toko',
        'jumlah_plastik_2_5kg',
        'jumlah_plastik_5kg',
        'harga_jual_per_plastik',
        'total_harga_jual',
        'keterangan',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}