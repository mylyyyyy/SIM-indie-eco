<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BukuKasIndie extends Model
{
    use HasFactory;

    protected $table = 'buku_kas_indies';

    protected $fillable = [
        'kode_transaksi', 'user_id', 'tanggal', 'jenis_transaksi', 
        'nominal', 'keterangan', 'dokumen_lampiran', 'status'
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->kode_transaksi = 'KAS-' . date('Ym') . '-' . rand(1000, 9999);
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}