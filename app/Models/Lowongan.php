<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Lowongan extends Model
{
    use HasFactory;
    protected $guarded = [];
    
    public function departemen()
    {
        return $this->belongsTo(Departemen::class, 'dept_id');
    }
}
