<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LhReportFoto extends Model
{
    protected $table = 'lh_report_fotos';
    protected $fillable = ['lh_report_id', 'path_foto'];

    public function laporan()
    {
        return $this->belongsTo(LhReport::class, 'lh_report_id');
    }
}