<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectReport extends Model
{
    use HasFactory;

    // Tambahkan ini agar data bisa disimpan
    protected $guarded = ['id']; 
    
    // ATAU gunakan fillable (pilih satu):
    // protected $fillable = [
    //     'user_id',
    //     'project_id',
    //     'report_date',
    //     'progress_percentage',
    //     'work_description',
    //     'documentation_path',
    //     'status',
    //     'admin_note'
    // ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    // Relasi ke payment (opsional, untuk nanti)
    public function payment()
    {
        return $this->hasOne(ProjectPayment::class, 'report_id');
    }
}