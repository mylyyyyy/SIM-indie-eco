<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanPresensi extends Model
{
    use HasFactory;

    protected $table = 'laporan_presensis';

    protected $fillable = [
        'kode_laporan', 'user_id', 'periode_bulan', 'status', 'catatan_evaluator',
        'total_pegawai', 'hadir', 'sakit', 'izin', 'alpa', 'keterangan', 'dokumen_lampiran'
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->kode_laporan = 'PRS-' . date('Ym') . '-' . rand(1000, 9999);
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}