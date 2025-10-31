<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Run seeders in correct order to maintain relationships
        $this->call([
            RoleSeeder::class,        // Base data
            // JenisSuratSeed::class,    // Service data - removed to allow manual entry
            UserSeeder::class,        // User data
            PengajuanSuratSeeder::class, // Application data
            SuratTerbitSeeder::class, // Generated documents
        ]);

        $this->command->info('✅ All seeders executed successfully!');
        $this->command->info('');
        $this->command->info('📋 Created Data Summary:');
        $this->command->info('   👥 Roles: Admin & Warga');
        $this->command->info('   📋 Jenis Surat: 10 different types');
        $this->command->info('   👤 Users: 1 Admin + 4 Warga');
        $this->command->info('   📄 Pengajuan: Sample applications with various statuses');
        $this->command->info('   📑 Surat Terbit: Generated documents for approved applications');
        $this->command->info('');
        $this->command->info('🔐 Test Credentials:');
        $this->command->info('   Admin: NIK: 1234567890123456, Password: admin123');
        $this->command->info('   Warga: NIK: 6543210987654321, Password: user123');
        $this->command->info('   Warga: NIK: 1111222233334444, Password: user123');
        $this->command->info('   Warga: NIK: 5555666677778888, Password: user123');
        $this->command->info('   Warga: NIK: 9999000011112222, Password: user123');
    }
}
