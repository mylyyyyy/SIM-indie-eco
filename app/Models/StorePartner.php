<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class StorePartner extends Model {
    use HasFactory;
    protected $fillable = ['tanggal_update', 'kantor_cabang', 'kode_toko', 'nama_toko', 'nama_pemilik', 'foto_toko', 'no_telp', 'catatan_status'];
}
