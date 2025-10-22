<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengajuan_surat', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('surat_type_id')->constrained('surat_types')->onDelete('cascade');
            $table->string('nomor_surat')->nullable();
            $table->dateTime('tanggal_pengajuan')->useCurrent();
            $table->enum('status', ['diajukan','ditolak','diverifikasi','diproses','ditandatangani','siap_dijemput'])->default('diajukan');
            $table->text('keterangan')->nullable();
            $table->string('tracking_code', 50)->unique();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengajuan_surat');
    }
};
