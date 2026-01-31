<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kunjungan extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama_pengunjung',
        'nik_pengunjung',
        'no_wa_pengunjung',
        'email_pengunjung',
        'alamat_pengunjung',
        'nama_wbp',
        'hubungan',
        'tanggal_kunjungan',
        'sesi',
        'nomor_antrian_harian',
        'status',
        'qr_token',
    ];
}
