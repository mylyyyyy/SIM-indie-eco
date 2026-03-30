<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArsipTransaksi extends Model
{
    use HasFactory;

    protected $table = 'arsip_transaksis';

    protected $fillable = [
        'kode_arsip', 'user_id', 'tanggal', 'kategori', 
        'nama_dokumen', 'nama_pihak_terkait', 'nominal', 'keterangan', 'dokumen_lampiran'
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->kode_arsip = 'ARS-' . date('Ym') . '-' . rand(1000, 9999);
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}