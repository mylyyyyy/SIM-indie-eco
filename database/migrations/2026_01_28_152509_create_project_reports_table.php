<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('project_reports', function (Blueprint $table) {
            $table->id();
            // Relasi ke User (Siapa Subkon yang lapor)
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            // Relasi ke Project (Laporan untuk proyek apa)
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            
            $table->date('report_date');
            $table->text('work_description');
            $table->integer('progress_percentage'); // 0 - 100
            
            // Kolom untuk upload gambar Scan/Foto
            $table->longText('documentation_path')->nullable(); // Bisa pakai text jika base64 panjang
            
            // Kolom Status Verifikasi
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('admin_note')->nullable(); // Catatan dari Keuangan/Admin
            $table->integer('rating')->nullable(); // Nilai kualitas kerja (opsional)

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('project_reports');
    }
};