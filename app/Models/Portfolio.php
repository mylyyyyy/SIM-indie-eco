<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Portfolio extends Model
{
    use HasFactory;

    protected $table = 'portfolios';

    protected $fillable = [
        'title', 'category', 'client_name', 'location', // Tambah location
        'completion_date', 'description', 'image_path', 
        'specs', // Tambah specs
    ];

    protected $casts = [
        'completion_date' => 'date',
        'specs' => 'array', // <--- PENTING: Agar JSON otomatis jadi Array PHP
    ];
}