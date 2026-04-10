<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubkonPtReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'project_name',
        'report_type',
        'periode',
        'tanggal_laporan',
        'progress_target',
        'progress_actual',
        'keterangan'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}