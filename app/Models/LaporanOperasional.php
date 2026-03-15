<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanOperasional extends Model
{
    use HasFactory;

    // Tabel yang digunakan
    protected $table = 'laporan_operasionals';

    protected $fillable = [
        'kode_laporan', 'user_id', 'periode_bulan', 'status', 'catatan_evaluator',
        'ringkasan_kegiatan', 'kendala_hambatan', 'dokumen_lampiran'
    ];

    // Auto generate kode laporan (OPX-TahunBulan-Random) saat pertama dibuat
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->kode_laporan = 'OPX-' . date('Ym') . '-' . rand(1000, 9999);
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}