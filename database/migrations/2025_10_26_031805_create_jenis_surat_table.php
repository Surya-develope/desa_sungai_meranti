<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
        public function up(): void {
            Schema::create('jenis_surat', function (Blueprint $table) {
                $table->id();
                $table->string('nama_surat', 150);
                $table->string('file_template');
                $table->json('form_structure')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

    public function down(): void {
        Schema::dropIfExists('jenis_surat');
    }
};
