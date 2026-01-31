<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
// use Laravel\Sanctum\HasApiTokens;  <-- HAPUS BARIS INI ATAU KOMENTARI

class User extends Authenticatable
{
    // HAPUS 'HasApiTokens' DARI DALAM SINI
    use HasFactory, Notifiable; 

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
       'name',
        'email',
        'password',
        'role',           // <--- Wajib ada
        'company_name',   // <--- Wajib ada
        'phone',          // <--- Wajib ada (jika ada di DB)
        'specialization', // <--- Wajib ada (jika ada di DB)
        'address',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Relasi ke Laporan (Jika user adalah Subkon Eks)
    public function reports()
    {
        return $this->hasMany(ProjectReport::class, 'user_id');
    }
}