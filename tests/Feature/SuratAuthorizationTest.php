<?php

namespace Tests\Feature;

use App\Models\Surat;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SuratAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    protected User $mahasiswa1;
    protected User $mahasiswa2;
    protected User $petugas;
    protected User $kepalaBagian;
    protected Surat $suratMahasiswa1;
    protected Surat $suratMahasiswa2;

    protected function setUp(): void
    {
        parent::setUp();

        // 1. Mahasiswa 1 (Ahmad Zaki)
        $this->mahasiswa1 = User::factory()->create([
            'name' => 'Ahmad Zaki',
            'email' => 'mahasiswa1@teknologi.ac.id',
            'role' => 'mahasiswa',
            'nim' => '2024001',
            'prodi' => 'Teknik Informatika',
        ]);

        // 2. Mahasiswa 2 (Budi Pratama)
        $this->mahasiswa2 = User::factory()->create([
            'name' => 'Budi Pratama',
            'email' => 'mahasiswa2@teknologi.ac.id',
            'role' => 'mahasiswa',
            'nim' => '2024002',
            'prodi' => 'Sistem Informasi',
        ]);

        // 3. Petugas Administrasi
        $this->petugas = User::factory()->create([
            'name' => 'Siti Rahma',
            'email' => 'petugas@teknologi.ac.id',
            'role' => 'petugas',
        ]);

        // 4. Kepala Bagian
        $this->kepalaBagian = User::factory()->create([
            'name' => 'Dr. Hendra',
            'email' => 'kabag@teknologi.ac.id',
            'role' => 'kepala_bagian',
        ]);

        // Sample Surat 1 milik Mahasiswa 1
        $this->suratMahasiswa1 = Surat::create([
            'user_id' => $this->mahasiswa1->id,
            'jenis_surat' => 'Surat Keterangan Aktif Kuliah',
            'keperluan' => 'Pengurusan beasiswa akademik',
            'status' => 'menunggu',
        ]);

        // Sample Surat 2 milik Mahasiswa 2
        $this->suratMahasiswa2 = Surat::create([
            'user_id' => $this->mahasiswa2->id,
            'jenis_surat' => 'Surat Pengantar Magang / PKL',
            'keperluan' => 'Magang kerja praktik di industri',
            'status' => 'diproses',
        ]);
    }

    /**
     * 1. Pengujian Guest: Tamu tanpa autentikasi diarahkan ke halaman login.
     */
    public function test_guest_is_redirected_to_login_when_accessing_protected_routes(): void
    {
        $this->get('/mahasiswa/dashboard')->assertRedirect('/login');
        $this->get('/petugas/dashboard')->assertRedirect('/login');
        $this->get('/kepala-bagian/dashboard')->assertRedirect('/login');
    }

    /**
     * 2. Pengujian Role Middleware: Mahasiswa hanya boleh akses route grup mahasiswa.
     */
    public function test_mahasiswa_can_access_mahasiswa_routes_but_forbidden_from_petugas_and_kabag(): void
    {
        $this->actingAs($this->mahasiswa1);

        // Berhasil akses route mahasiswa
        $this->get('/mahasiswa/dashboard')->assertStatus(200);
        $this->get('/mahasiswa/surat')->assertStatus(200);

        // DITOLAK 403 Forbidden ketika mencoba membuka route petugas atau kabag
        $this->get('/petugas/dashboard')->assertStatus(403);
        $this->get('/petugas/surat')->assertStatus(403);
        $this->get('/kepala-bagian/dashboard')->assertStatus(403);
        $this->get('/kepala-bagian/surat')->assertStatus(403);
    }

    /**
     * 3. Pengujian Role Middleware: Petugas tidak boleh akses route grup kepala bagian atau mahasiswa.
     */
    public function test_petugas_can_access_petugas_routes_but_forbidden_from_kabag_and_mahasiswa(): void
    {
        $this->actingAs($this->petugas);

        $this->get('/petugas/dashboard')->assertStatus(200);
        $this->get('/petugas/surat')->assertStatus(200);

        $this->get('/mahasiswa/dashboard')->assertStatus(403);
        $this->get('/kepala-bagian/dashboard')->assertStatus(403);
    }

    /**
     * 4. Pengujian Role Middleware: Kepala Bagian hanya dapat mengakses route kepala bagian.
     */
    public function test_kepala_bagian_can_access_kabag_routes_but_forbidden_from_petugas_and_mahasiswa(): void
    {
        $this->actingAs($this->kepalaBagian);

        $this->get('/kepala-bagian/dashboard')->assertStatus(200);
        $this->get('/kepala-bagian/surat')->assertStatus(200);

        $this->get('/mahasiswa/dashboard')->assertStatus(403);
        $this->get('/petugas/dashboard')->assertStatus(403);
    }

    /**
     * 5. Pengujian Kepemilikan Data (Data Ownership Authorization):
     * Mahasiswa DAPAT melihat surat miliknya sendiri.
     */
    public function test_mahasiswa_can_view_own_surat(): void
    {
        $this->actingAs($this->mahasiswa1);

        $response = $this->get('/mahasiswa/surat/' . $this->suratMahasiswa1->id);
        $response->assertStatus(200);
        $response->assertSee('Surat Keterangan Aktif Kuliah');
    }

    /**
     * 6. TITIK KRUSIAL KASUS: URL ID Tampering Prevention!
     * Mahasiswa 1 mencoba mengakses surat milik Mahasiswa 2 dengan mengganti ID di URL browser.
     * Sistem HARUS menggagalkannya dengan HTTP 403 Forbidden.
     */
    public function test_mahasiswa_forbidden_403_when_tampering_url_to_view_other_student_surat(): void
    {
        $this->actingAs($this->mahasiswa1);

        // Mahasiswa 1 mengakses URL surat ID milik Mahasiswa 2
        $response = $this->get('/mahasiswa/surat/' . $this->suratMahasiswa2->id);

        // Wajib menghasilkan 403 Forbidden (SuratPolicy view check gagal)
        $response->assertStatus(403);
    }

    /**
     * 7. Mahasiswa tidak dapat membatalkan/menghapus surat milik mahasiswa lain.
     */
    public function test_mahasiswa_forbidden_from_deleting_other_student_surat(): void
    {
        $this->actingAs($this->mahasiswa1);

        $response = $this->delete('/mahasiswa/surat/' . $this->suratMahasiswa2->id);

        $response->assertStatus(403);
    }

    /**
     * 8. Mahasiswa dapat mengajukan permohonan surat baru.
     */
    public function test_mahasiswa_can_create_new_surat(): void
    {
        $this->actingAs($this->mahasiswa1);

        $response = $this->post('/mahasiswa/surat', [
            'jenis_surat' => 'Surat Izin Penelitian',
            'keperluan' => 'Pengambilan data skripsi untuk riset AI di laboratorium',
            'keterangan_tambahan' => 'Lab Sistem Cerdas',
        ]);

        $this->assertDatabaseHas('surats', [
            'user_id' => $this->mahasiswa1->id,
            'jenis_surat' => 'Surat Izin Penelitian',
            'status' => 'menunggu',
        ]);

        $response->assertSessionHas('success');
    }

    /**
     * 9. Petugas Administrasi dapat memproses surat mahasiswa.
     */
    public function test_petugas_can_process_surat(): void
    {
        $this->actingAs($this->petugas);

        $response = $this->post('/petugas/surat/' . $this->suratMahasiswa1->id . '/proses', [
            'status' => 'diproses',
            'nomor_surat' => 'DRAFT-001/FT/2026',
            'catatan_petugas' => 'Berkas KRS valid, lanjut ke kabag',
        ]);

        $this->suratMahasiswa1->refresh();

        $this->assertEquals('diproses', $this->suratMahasiswa1->status);
        $this->assertEquals('DRAFT-001/FT/2026', $this->suratMahasiswa1->nomor_surat);
        $this->assertEquals($this->petugas->id, $this->suratMahasiswa1->petugas_id);
    }

    /**
     * 10. Kepala Bagian dapat menyetujui surat dan menerbitkan nomor surat resmi.
     */
    public function test_kepala_bagian_can_approve_surat(): void
    {
        $this->actingAs($this->kepalaBagian);

        $response = $this->post('/kepala-bagian/surat/' . $this->suratMahasiswa1->id . '/setujui', [
            'nomor_surat' => '001/FT-AKD/UNIV/03/2026',
            'catatan_kepala_bagian' => 'Disetujui resmi oleh Kepala Bagian',
        ]);

        $this->suratMahasiswa1->refresh();

        $this->assertEquals('disetujui', $this->suratMahasiswa1->status);
        $this->assertEquals('001/FT-AKD/UNIV/03/2026', $this->suratMahasiswa1->nomor_surat);
        $this->assertEquals($this->kepalaBagian->id, $this->suratMahasiswa1->kepala_bagian_id);
    }
}
