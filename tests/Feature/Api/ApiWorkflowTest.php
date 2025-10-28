<?php

namespace Tests\Feature\Api;

use App\Models\JenisSurat;
use App\Models\PengajuanSurat;
use App\Models\Role;
use App\Models\SuratTerbit;
use App\Models\UserDesa;
use App\Services\SuratGeneratorService;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ApiWorkflowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleSeeder::class);
    }

    public function test_register_endpoint_returns_expected_payload(): void
    {
        $payload = [
            'nik' => '9999999999999999',
            'nama' => 'Registrasi Warga',
            'email' => 'register@example.com',
            'password' => 'password123',
        ];

        $response = $this->postJson('/api/register', $payload);

        $response
            ->assertStatus(201)
            ->assertJson([
                'success' => true,
                'message' => 'Registrasi berhasil',
                'user' => [
                    'nik' => $payload['nik'],
                    'nama' => $payload['nama'],
                    'email' => $payload['email'],
                    'role_id' => 2,
                ],
            ])
            ->assertJsonStructure([
                'token',
                'user' => ['nik', 'nama', 'email', 'role_id'],
            ]);

        $this->assertDatabaseHas('user_desa', [
            'nik' => $payload['nik'],
            'email' => $payload['email'],
        ]);
    }

    public function test_login_endpoint_allows_registered_user_to_authenticate(): void
    {
        $wargaRole = Role::firstOrCreate(['nama_role' => 'warga']);

        $user = UserDesa::create([
            'nik' => '2222222222222222',
            'nama' => 'Login Warga',
            'email' => 'login@example.com',
            'password' => 'password123',
            'role_id' => $wargaRole->id,
        ]);

        $response = $this->postJson('/api/login', [
            'nik' => $user->nik,
            'password' => 'password123',
        ]);

        $response
            ->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Login berhasil',
                'user' => [
                    'nik' => $user->nik,
                    'nama' => $user->nama,
                    'email' => $user->email,
                    'role_id' => $user->role_id,
                ],
            ])
            ->assertJsonStructure([
                'token',
                'user' => ['nik', 'nama', 'email', 'role_id'],
            ]);
    }

    public function test_full_pengajuan_flow_from_submission_to_document_generation(): void
    {
        $adminRole = Role::firstOrCreate(['nama_role' => 'admin']);
        $wargaRole = Role::firstOrCreate(['nama_role' => 'warga']);

        $admin = UserDesa::create([
            'nik' => '1111111111111111',
            'nama' => 'Admin Test',
            'email' => 'admin@example.com',
            'password' => 'password123',
            'role_id' => $adminRole->id,
        ]);

        $warga = UserDesa::create([
            'nik' => '3333333333333333',
            'nama' => 'Warga Pemohon',
            'email' => 'warga@example.com',
            'password' => 'password123',
            'role_id' => $wargaRole->id,
        ]);

        $jenisSurat = JenisSurat::create([
            'nama_surat' => 'Surat Keterangan Domisili',
            'file_template' => 'template_domisili.docx',
            'deskripsi' => 'Surat keterangan domisili',
            'is_active' => true,
        ]);

        $this->instance(SuratGeneratorService::class, new class {
            public function generateFromTemplate(PengajuanSurat $pengajuan): array
            {
                return [
                    'path' => 'surat/generated.pdf',
                    'url' => '/storage/surat/generated.pdf',
                    'docx' => 'surat/generated.docx',
                    'pdf' => 'surat/generated.pdf',
                ];
            }
        });

        Sanctum::actingAs($warga, ['*']);

        $submitResponse = $this->postJson('/api/pengajuan', [
            'jenis_surat_id' => $jenisSurat->id,
            'data_pemohon' => [
                'nama' => $warga->nama,
                'nik_pemohon' => $warga->nik,
                'alamat' => 'Jl. Pengujian No. 1',
            ],
            'keterangan' => 'Permohonan surat keterangan domisili',
        ]);

        $submitResponse
            ->assertStatus(201)
            ->assertJson([
                'success' => true,
                'message' => 'Pengajuan berhasil dikirim',
            ])
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'nik_pemohon',
                    'jenis_surat_id',
                    'tanggal_pengajuan',
                    'status',
                    'data_isian',
                    'file_syarat',
                ],
            ]);

        $pengajuanId = $submitResponse->json('data.id');

        $this->assertDatabaseHas('pengajuan_surat', [
            'id' => $pengajuanId,
            'nik_pemohon' => $warga->nik,
            'status' => 'menunggu',
        ]);

        $detailResponse = $this->getJson("/api/pengajuan/{$pengajuanId}");
        $detailResponse
            ->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'id' => $pengajuanId,
                    'status' => 'menunggu',
                ],
            ]);

        Sanctum::actingAs($admin, ['*']);

        $approveResponse = $this->postJson("/api/admin/pengajuan/{$pengajuanId}/approve");
        $approveResponse
            ->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Pengajuan disetujui, siap digenerate',
            ]);

        $this->assertDatabaseHas('pengajuan_surat', [
            'id' => $pengajuanId,
            'status' => 'disetujui_verifikasi',
        ]);

        $generateResponse = $this->postJson("/api/admin/pengajuan/{$pengajuanId}/generate");
        $generateResponse
            ->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Dokumen berhasil dibuat',
                'file' => '/storage/surat/generated.pdf',
            ]);

        $this->assertDatabaseHas('pengajuan_surat', [
            'id' => $pengajuanId,
            'status' => 'menunggu_tanda_tangan',
        ]);

        $this->assertDatabaseHas('surat_terbit', [
            'pengajuan_id' => $pengajuanId,
            'file_surat' => 'surat/generated.pdf',
        ]);

        $detailAfterResponse = $this->getJson("/api/pengajuan/{$pengajuanId}");
        $detailAfterResponse
            ->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'id' => $pengajuanId,
                    'status' => 'menunggu_tanda_tangan',
                ],
            ])
            ->assertJsonStructure([
                'data' => [
                    'surat_terbit',
                ],
            ]);
    }
}