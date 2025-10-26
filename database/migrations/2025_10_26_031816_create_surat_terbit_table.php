<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('surat_terbit', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pengajuan_id');
            $table->string('file_surat')->nullable();
            $table->date('tanggal_terbit')->nullable();
            $table->string('status_cetak', 50)->default('belum dicetak');

            $table->foreign('pengajuan_id')->references('id')->on('pengajuan_surat')->onDelete('cascade');
        });
    }

    public function down(): void {
        Schema::dropIfExists('surat_terbit');
    }
};
