<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('jenis_surat', function (Blueprint $table) {
            if (!Schema::hasColumn('jenis_surat', 'deskripsi')) {
                $table->text('deskripsi')->nullable()->after('file_template');
            }
            
            if (!Schema::hasColumn('jenis_surat', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('deskripsi');
            }
            
            if (!Schema::hasColumn('jenis_surat', 'updated_at')) {
                $table->timestamp('updated_at')->useCurrent()->nullable()->after('created_at');
            }
            
            // Tambah index jika belum ada
            if (!Schema::hasColumn('jenis_surat', 'jenis_surat_active_nama_surat_index')) {
                $table->index(['is_active', 'nama_surat']);
            }
        });
    }

    public function down(): void {
        Schema::table('jenis_surat', function (Blueprint $table) {
            $table->dropIndex(['is_active', 'nama_surat']);
            $table->dropColumn(['deskripsi', 'is_active', 'updated_at']);
        });
    }
};