<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogbookSurat extends Model
{
    use HasFactory;

    protected $table = 'logbook_surats';

    protected $fillable = [
        'kode_laporan', 'user_id', 'periode_bulan', 'status', 'catatan_evaluator',
        'jumlah_surat_masuk', 'jumlah_surat_keluar', 'keterangan', 'dokumen_lampiran'
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->kode_laporan = 'LOG-' . date('Ym') . '-' . rand(1000, 9999);
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}