<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class VisitResult extends Model {
    use HasFactory;
    protected $fillable = ['hari', 'tanggal', 'nama_toko', 'alamat', 'titip_sisa_awal_pack', 'harga_rp', 'laku_pack', 'sisa_pack', 'tambah_pack', 'total_pack', 'keterangan_bayar'];
}