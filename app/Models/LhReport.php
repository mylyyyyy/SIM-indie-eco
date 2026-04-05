<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LhReport extends Model
{
    // Tambahkan nama_cabang ke dalam fillable
    protected $fillable = [
        'user_id', 'nama_cabang', 'tanggal', 'rincian_kegiatan', 'dokumentasi' 
    ];

    // Tambahkan relasi ini
    public function fotos()
    {
        return $this->hasMany(LhReportFoto::class, 'lh_report_id');
    }


    // Penting: Casting JSON agar otomatis jadi Array saat diambil
    protected $casts = [
        'rincian_kegiatan' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }
}