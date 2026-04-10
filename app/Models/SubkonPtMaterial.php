<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubkonPtMaterial extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'project_name',
        'tanggal',
        'nama_material',
        'satuan',
        'stok_awal',
        'material_masuk',
        'material_terpakai',
        'sisa_stok',
        'keterangan'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}