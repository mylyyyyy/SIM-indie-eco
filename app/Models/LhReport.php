<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LhReport extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    // Penting: Casting JSON agar otomatis jadi Array saat diambil
    protected $casts = [
        'rincian_kegiatan' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }
}