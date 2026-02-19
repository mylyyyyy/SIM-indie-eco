<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class VisitPlan extends Model {
    use HasFactory;
    protected $fillable = ['nama_toko', 'alamat', 'stok_awal', 'harga', 'laku_pack', 'sisa_pack', 'tambah_pack'];
}
