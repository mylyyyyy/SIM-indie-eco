<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectPayment extends Model
{
    use HasFactory;

    // Izinkan semua kolom diisi (atau gunakan $fillable jika mau spesifik)
    protected $guarded = ['id']; 

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function requestor() // Relasi ke Subkon
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function finance() // Relasi ke Keuangan
    {
        return $this->belongsTo(User::class, 'finance_user_id');
    }
}