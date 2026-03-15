<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RekapAnggaranOpex extends Model
{
    use HasFactory;

    protected $table = 'rekap_anggaran_opex';

    protected $fillable = [
        'kode_laporan', 'user_id', 'periode_bulan', 'status', 'catatan_evaluator',
        'total_anggaran', 'total_pengeluaran', 'keterangan', 'dokumen_lampiran'
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            // Format ID Laporan: OPEX-TahunBulan-Random
            $model->kode_laporan = 'OPEX-' . date('Ym') . '-' . rand(1000, 9999);
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}