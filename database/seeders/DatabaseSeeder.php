<?php

namespace Database\Seeders;

use App\Models\Surat;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Akun Mahasiswa 1
        $mhs1 = User::create([
            'name' => 'Ahmad Zaki Al-Faruq',
            'email' => 'mahasiswa1@teknologi.ac.id',
            'role' => 'mahasiswa',
            'nim' => '2024001',
            'prodi' => 'Teknik Informatika',
            'no_hp' => '081234567890',
            'password' => Hash::make('password'),
        ]);

        // 2. Akun Mahasiswa 2
        $mhs2 = User::create([
            'name' => 'Budi Pratama Wijaya',
            'email' => 'mahasiswa2@teknologi.ac.id',
            'role' => 'mahasiswa',
            'nim' => '2024002',
            'prodi' => 'Sistem Informasi',
            'no_hp' => '081298765432',
            'password' => Hash::make('password'),
        ]);

        // 3. Akun Petugas Tata Usaha
        $petugas = User::create([
            'name' => 'Siti Rahma, S.Kom.',
            'email' => 'petugas@teknologi.ac.id',
            'role' => 'petugas',
            'password' => Hash::make('password'),
        ]);

        // 4. Akun Kepala Bagian
        $kabag = User::create([
            'name' => 'Dr. Ir. Hendra Gunawan, M.T.',
            'email' => 'kabag@teknologi.ac.id',
            'role' => 'kepala_bagian',
            'password' => Hash::make('password'),
        ]);

        // Seed Data Surat untuk Mahasiswa 1
        Surat::create([
            'user_id' => $mhs1->id,
            'jenis_surat' => 'Surat Keterangan Aktif Kuliah',
            'keperluan' => 'Permohonan beasiswa Bank Indonesia Tahun Akademik 2026/2027',
            'keterangan_tambahan' => 'Semester 6 Reguler Pagi',
            'status' => 'menunggu',
        ]);

        Surat::create([
            'user_id' => $mhs1->id,
            'nomor_surat' => '045/FT-TI/AKD/03/2026',
            'jenis_surat' => 'Surat Izin Penelitian',
            'keperluan' => 'Pengambilan data skripsi topik Analisis Sistem Keamanan Jaringan di PT Telkom Indonesia',
            'keterangan_tambahan' => 'Instansi: PT Telkom Witel Jakarta Selatan, Periode: Maret - Mei 2026',
            'status' => 'disetujui',
            'catatan_petugas' => 'Proposal skripsi dan persetujuan dosen pembimbing lengkap dan valid.',
            'catatan_kepala_bagian' => 'Telah diverifikasi dan disetujui resmi untuk pelaksanaan penelitian skripsi.',
            'petugas_id' => $petugas->id,
            'kepala_bagian_id' => $kabag->id,
            'tanggal_diproses' => now()->subDays(2),
            'tanggal_selesai' => now()->subDay(),
        ]);

        // Seed Data Surat untuk Mahasiswa 2
        Surat::create([
            'user_id' => $mhs2->id,
            'nomor_surat' => '088/FT-SI/MAG/03/2026',
            'jenis_surat' => 'Surat Pengantar Magang / PKL',
            'keperluan' => 'Kerja Praktik industri di PT Bank Central Asia Tbk Divisi Digital Engineering',
            'keterangan_tambahan' => 'Periode Magang: 1 Juli - 31 Agustus 2026 (Durasi 2 Bulan)',
            'status' => 'diproses',
            'catatan_petugas' => 'KRS aktif semester 5 telah diverifikasi, mahasiswa telah lulus 90 SKS.',
            'petugas_id' => $petugas->id,
            'tanggal_diproses' => now()->subHours(5),
        ]);

        Surat::create([
            'user_id' => $mhs2->id,
            'jenis_surat' => 'Surat Keterangan Berkelakuan Baik',
            'keperluan' => 'Kelengkapan administrasi seleksi pertukaran mahasiswa luar negeri',
            'keterangan_tambahan' => 'Program IISMA Kemendikbudristek 2026',
            'status' => 'ditolak',
            'catatan_petugas' => 'Berkas KRS dan catatan komisi disiplin terlampir.',
            'catatan_kepala_bagian' => 'Ditolak: Mahasiswa belum melampirkan surat rekomendasi bebas sanksi akademik dari Ketua Program Studi.',
            'petugas_id' => $petugas->id,
            'kepala_bagian_id' => $kabag->id,
            'tanggal_diproses' => now()->subDays(4),
            'tanggal_selesai' => now()->subDays(3),
        ]);
    }
}
