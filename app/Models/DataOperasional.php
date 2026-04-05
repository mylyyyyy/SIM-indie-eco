<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataOperasional extends Model
{
    use HasFactory;

    protected $table = 'data_operasional_indie';

    protected $fillable = [
        'user_id', 'kode_referensi', 'modul_laporan', 'tanggal_periode', 
        'status', 'keterangan_umum', 'catatan_evaluator', 'dokumen_lampiran', 'data_spesifik'
    ];

    // Konversi otomatis string JSON di database menjadi array PHP saat dipanggil
    protected $casts = [
        'data_spesifik' => 'array',
        'tanggal_periode' => 'date'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}