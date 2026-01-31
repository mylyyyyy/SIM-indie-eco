<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $guarded = ['id']; // Semua kolom bisa diisi kecuali ID

    // Relasi ke Laporan
    public function reports()
    {
        return $this->hasMany(ProjectReport::class);
    }

    // Relasi ke Penilaian
    public function ratings()
    {
        return $this->hasMany(ContractorRating::class);
    }
}