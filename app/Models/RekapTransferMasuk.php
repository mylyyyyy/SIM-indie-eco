<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RekapTransferMasuk extends Model
{
    use HasFactory;

    protected $table = 'rekap_transfer_masuks';

    protected $fillable = [
        'kode_transfer', 'user_id', 'tanggal_transfer', 'nama_pengirim', 
        'bank_asal', 'bank_tujuan', 'nominal', 'kategori_dana', 
        'keterangan', 'dokumen_lampiran', 'status'
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->kode_transfer = 'TRF-' . date('Ym') . '-' . rand(1000, 9999);
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}