<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('user_desa', function (Blueprint $table) {
            $table->string('nik', 20)->primary();
            $table->string('nama', 100);
            $table->string('email')->unique();
            $table->string('password');
            $table->unsignedBigInteger('role_id');
            $table->text('alamat')->nullable();
            $table->string('no_hp', 20)->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->foreign('role_id')->references('id')->on('role')->onDelete('cascade');
        });
    }

    public function down(): void {
        Schema::dropIfExists('user_desa');
    }
};
