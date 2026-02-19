<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class StoreRiceStock extends Model {
    use HasFactory;
    protected $fillable = ['tanggal', 'nama_admin', 'nama_toko', 'stok_2_5kg', 'stok_5kg'];
}
