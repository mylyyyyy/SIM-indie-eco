<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectReport extends Model
{
    use HasFactory;

    protected $guarded = ['id']; // Izinkan mass assignment kecuali ID

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke Project
    public function project()
    {
        return $this->belongsTo(Project::class);
        
    }
    public function payment()
{
    // Relasi 1 Laporan punya 1 Data Pembayaran
    return $this->hasOne(ProjectPayment::class, 'report_id');
}
}