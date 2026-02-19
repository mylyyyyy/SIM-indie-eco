<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class MillingReport extends Model {
    use HasFactory;
    protected $fillable = ['bulan', 'tanggal', 'ambil_beras_kg', 'jumlah_pack'];
}