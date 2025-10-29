<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('pengajuan_surat', function (Blueprint $table) {
            $table->id();
            $table->string('nik_pemohon', 16);
            $table->unsignedBigInteger('jenis_surat_id');
            $table->date('tanggal_pengajuan');
            $table->string('status', 50)->default('menunggu');
            $table->json('data_isian')->nullable();
            $table->json('file_syarat')->nullable();
            $table->text('alasan_penolakan')->nullable();
            $table->timestamps();

            $table->foreign('nik_pemohon')->references('nik')->on('user_desa')->onDelete('restrict');
            $table->foreign('jenis_surat_id')->references('id')->on('jenis_surat')->onDelete('restrict');
        });
    }

    public function down(): void {
        Schema::dropIfExists('pengajuan_surat');
    }
};
