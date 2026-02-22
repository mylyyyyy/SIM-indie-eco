<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class SoldRice extends Model {
    use HasFactory;
    // Tentukan nama tabel manual karena plural bahasa inggrisnya bisa ambigu
    protected $table = 'sold_rices'; 
    protected $fillable = ['user_id','tempat', 'tanggal', 'nama_toko', 'kunjungan_ke', 'ukuran'];
public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }
    }
