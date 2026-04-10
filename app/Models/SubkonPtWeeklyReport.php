<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubkonPtWeeklyReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'project_name',
        'minggu_ke',
        'periode_mulai',
        'periode_selesai',
        'progress_minggu_ini',
        'pekerjaan_diselesaikan',
        'rencana_minggu_depan',
        'kendala'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}