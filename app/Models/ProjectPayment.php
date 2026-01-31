<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectPayment extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    // Relasi: Setiap pembayaran milik 1 Laporan Kerja
    public function report()
    {
        return $this->belongsTo(ProjectReport::class, 'report_id');
    }

    // Relasi: Siapa staff keuangan yang memproses
    public function financeStaff()
    {
        return $this->belongsTo(User::class, 'finance_user_id');
    }
}