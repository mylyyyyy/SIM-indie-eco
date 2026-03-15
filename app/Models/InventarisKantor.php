<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventarisKantor extends Model
{
    use HasFactory;

    protected $table = 'inventaris_kantors';

    protected $fillable = [
        'kode_laporan', 'user_id', 'periode_bulan', 'status', 'catatan_evaluator',
        'total_aset_baik', 'total_aset_rusak', 'keterangan', 'dokumen_lampiran'
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->kode_laporan = 'INV-' . date('Ym') . '-' . rand(1000, 9999);
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}