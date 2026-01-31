<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContractorRating extends Model
{
    protected $guarded = ['id'];

    // Relasi ke Project
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    // Relasi ke Penilai (Subkon PT)
    public function rater()
    {
        return $this->belongsTo(User::class, 'rater_id');
    }

    // Relasi ke Target (Subkon Eks)
    public function target()
    {
        return $this->belongsTo(User::class, 'target_id');
    }
}