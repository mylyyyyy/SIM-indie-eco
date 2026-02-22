<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class PlasticStock extends Model {
    use HasFactory;
    protected $fillable = ['user_id','tempat', 'tanggal', 'jenis_plastik', 'stok_awal', 'stok_sisa'];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }
}
