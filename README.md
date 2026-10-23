# Layanan Surat Mahasiswa Online (SuratOnline FT)
> **Studi Kasus 2: Layanan Surat Online — Middleware + Route Group + Otorisasi Kepemilikan Data**  
> Fakultas Teknologi — Implementasi Framework Laravel 12 & Laravel Breeze

---

## 📌 1. Latar Belakang Kasus
Bagian Administrasi Fakultas Teknologi menerima ratusan permohonan surat administrasi setiap minggu (seperti Surat Keterangan Aktif Kuliah, Surat Izin Penelitian, Surat Pengantar Magang, dll). Sebelumnya, mahasiswa harus datang langsung secara fisik, sementara petugas tata usaha kesulitan mengidentifikasi status berkas (baru masuk, sedang diproses, atau telah selesai).

Aplikasi ini dibangun untuk mendigitalkan seluruh alur birokrasi surat dengan 3 jenis peran (*roles*):
1. **Mahasiswa**: Mengajukan surat dan hanya dapat melihat serta memantau status permohonan miliknya sendiri.
2. **Petugas Administrasi**: Memeriksa kelengkapan berkas permohonan mahasiswa, menyiapkan draft nomor surat, dan memproses status administrasi.
3. **Kepala Bagian**: Mengevaluasi permohonan, memberikan keputusan resmi, menyetujui penerbitan nomor surat sah, atau menolak permohonan disertai alasan transparan.

### Masalah Keamanan Utama yang Diatasi:
> *"Suatu hari, seorang mahasiswa mencoba membuka URL detail surat milik mahasiswa lain dengan mengganti ID pada alamat browser. Tim pengembang harus memastikan bahwa pengguna tidak dapat mengakses data yang bukan haknya."*

Sistem ini menerapkan pertahanan berlapis:
- **Perimeter Luar (Route Level)**: `RoleMiddleware` mengelompokkan akses menu berdasarkan peran akun.
- **Perimeter Dalam (Object Level)**: `SuratPolicy` memvalidasi kepemilikan baris data (*Data Ownership Authorization*) sehingga upaya manipulasi parameter ID pada URL browser (*Insecure Direct Object Reference / IDOR*) langsung digagalkan dengan respon **HTTP 403 Forbidden**.

---

## 🚀 2. Fitur Utama Aplikasi

### A. Autentikasi & Multi-Role (Laravel Breeze)
- Autentikasi sesi aman (*Session-based authentication*).
- Registrasi Mahasiswa Baru lengkap dengan validasi NIM dan Program Studi.
- Dashboard dinamis yang secara otomatis mengarahkan (*redirect*) pengguna ke dasbor perannya masing-masing setelah login.
- Tombol *Quick-Fill Demo* pada halaman login untuk pengujian cepat.

### B. Modul Mahasiswa (`/mahasiswa/*`)
- **Dashboard Metrik**: Ringkasan jumlah permohonan (Total, Menunggu, Diproses, Disetujui, Ditolak).
- **Formulir Pengajuan Surat**: Pilihan jenis surat, deskripsi keperluan, keterangan instansi tujuan, serta upload berkas pendukung (PDF, JPG, PNG maks 2MB).
- **Pelacakan Status Real-time**: Indikator tahapan visual (*stepper timeline*) dari permohonan masuk &rarr; verifikasi petugas &rarr; pengesahan kepala bagian.
- **Pembatalan Mandiri**: Mahasiswa dapat membatalkan permohonan selama berkas masih berstatus `menunggu`.

### C. Modul Petugas Administrasi (`/petugas/*`)
- **Dashboard Antrean**: Rekapitulasi permohonan masuk yang membutuhkan tindakan verifikasi.
- **Penyaringan & Pencarian**: Filter surat berdasarkan status (`menunggu`, `diproses`, `disetujui`, `ditolak`) dan pencarian berdasarkan nama/NIM/surat.
- **Verifikasi Dokumen**: Pemeriksaan berkas unduhan, pengisian catatan verifikasi petugas, dan penginputan draf penomoran surat.

### D. Modul Kepala Bagian (`/kepala-bagian/*`)
- **Dashboard Otorisasi**: Daftar permohonan yang telah diverifikasi petugas dan siap disahkan.
- **Pengesahan Resmi (Approval)**: Menerbitkan nomor surat resmi dan mengesahkan secara elektronik.
- **Penolakan Dokumen (Reject)**: Menolak berkas yang tidak memenuhi standar kelayakan disertai catatan alasan penolakan.
- **Pratinjau KOP & Surat Resmi**: Tampilan lembar surat keterangan resmi ber-kop Fakultas Teknologi ketika status telah `disetujui`.

---

## 🔐 3. Arsitektur Keamanan & Hak Akses

### A. Matriks Hak Akses Rute (Route Matrix)

| Endpoint / URL | Peran Mahasiswa | Peran Petugas | Peran Kepala Bagian | Tamu / Guest |
|---|:---:|:---:|:---:|:---:|
| `GET /` & `GET /login` | ✅ Akses | ✅ Akses | ✅ Akses | ✅ Akses |
| `GET /mahasiswa/*` | ✅ Diizinkan | ❌ 403 Forbidden | ❌ 403 Forbidden | 🔄 Redirect Login |
| `GET /petugas/*` | ❌ 403 Forbidden | ✅ Diizinkan | ❌ 403 Forbidden | 🔄 Redirect Login |
| `GET /kepala-bagian/*` | ❌ 403 Forbidden | ❌ 403 Forbidden | ✅ Diizinkan | 🔄 Redirect Login |

### B. Proteksi Kepemilikan Data (*Preventing URL ID Tampering*)
Pemeriksaan dilakukan melalui `App\Policies\SuratPolicy`:

```php
public function view(User $user, Surat $surat): Response
{
    // Jika mahasiswa, periksa kesesuaian user_id pemilik surat
    if ($user->isMahasiswa()) {
        return $user->id === $surat->user_id
            ? Response::allow()
            : Response::deny('Akses Ditolak: Anda tidak memiliki izin untuk melihat pengajuan surat milik mahasiswa lain.');
    }

    // Petugas & Kepala Bagian berwenang memeriksa seluruh dokumen fakultas
    if ($user->isPetugas() || $user->isKepalaBagian()) {
        return Response::allow();
    }

    return Response::deny('Akses Ditolak.');
}
```

Pada controller dipanggil dengan `Gate::authorize('view', $surat)`. Jika Mahasiswa A (ID: 1) mencoba mengakses `/mahasiswa/surat/3` (milik Mahasiswa B), Laravel otomatis menghentikan eksekusi dan merespons **403 Forbidden**.

---

## 👥 4. Akun Demo untuk Pengujian

Semua akun menggunakan kata sandi (*password*) yang sama: `password`.

| Peran Akun | Nama Lengkap | Email Login | Password | Keterangan Uji |
|---|---|---|---|---|
| **Mahasiswa 1** | Ahmad Zaki Al-Faruq | `mahasiswa1@teknologi.ac.id` | `password` | Pemilik Surat ID #1 (Menunggu) & ID #2 (Disetujui) |
| **Mahasiswa 2** | Budi Pratama Wijaya | `mahasiswa2@teknologi.ac.id` | `password` | Pemilik Surat ID #3 (Diproses) & ID #4 (Ditolak) |
| **Petugas TU** | Siti Rahma, S.Kom. | `petugas@teknologi.ac.id` | `password` | Mengelola & memproses berkas masuk |
| **Kepala Bagian**| Dr. Ir. Hendra Gunawan, M.T. | `kabag@teknologi.ac.id` | `password` | Mengesahkan nomor surat / menolak |

---

## 🛠️ 5. Panduan Instalasi & Menjalankan Aplikasi

### Kebutuhan Sistem:
- PHP >= 8.2 (ekstensi sqlite3, pdo_sqlite, mbstring, openssl aktif)
- Composer >= 2.x
- Node.js & NPM

### Langkah Menjalankan:
1. **Clone / Buka Direktori Proyek**:
   ```bash
   cd c:\xampp\htdocs\UTS_webIII
   ```

2. **Salin Environment & Konfigurasi Basis Data**:
   Konfigurasi `.env` telah diset menggunakan SQLite:
   ```env
   DB_CONNECTION=sqlite
   ```

3. **Jalankan Migrasi & Seeder Data Demo**:
   ```bash
   php artisan migrate:fresh --seed
   ```

4. **Hubungkan Symlink Penyimpanan Berkas**:
   ```bash
   php artisan storage:link
   ```

5. **Build Aset Frontend (Tailwind CSS)**:
   ```bash
   npm run build
   ```

6. **Jalankan Server Lokal**:
   ```bash
   php artisan serve --port=8000
   ```
   Buka browser pada alamat: **`http://127.0.0.1:8000`**

---

## 🧪 6. Pengujian Sistem

### A. Pengujian Otomatis (Automated Tests)
Jalankan pengujian unit dan fitur untuk memastikan integritas middleware dan otorisasi:
```bash
php artisan test --filter=SuratAuthorizationTest
```
**Hasil Test Suite (10 Passed, 32 Assertions):**
* `test_guest_is_redirected_to_login_when_accessing_protected_routes` (Passed)
* `test_mahasiswa_can_access_mahasiswa_routes_but_forbidden_from_petugas_and_kabag` (Passed)
* `test_petugas_can_access_petugas_routes_but_forbidden_from_kabag_and_mahasiswa` (Passed)
* `test_kepala_bagian_can_access_kabag_routes_but_forbidden_from_petugas_and_mahasiswa` (Passed)
* `test_mahasiswa_can_view_own_surat` (Passed)
* `test_mahasiswa_forbidden_403_when_tampering_url_to_view_other_student_surat` (Passed)
* `test_mahasiswa_forbidden_from_deleting_other_student_surat` (Passed)
* `test_mahasiswa_can_create_new_surat` (Passed)
* `test_petugas_can_process_surat` (Passed)
* `test_kepala_bagian_can_approve_surat` (Passed)

### B. Prosedur Uji Manual Manipulasi URL di Browser
1. Buka browser dan login sebagai `mahasiswa1@teknologi.ac.id`.
2. Klik surat ID #1 atau buka URL: `http://127.0.0.1:8000/mahasiswa/surat/1` &rarr; Halaman detail terbuka dengan benar.
3. Ubah ID pada URL browser secara manual menjadi: `http://127.0.0.1:8000/mahasiswa/surat/3` (surat milik Budi/Mahasiswa 2).
4. Tekan Enter &rarr; Muncul halaman **403 | THIS ACTION IS UNAUTHORIZED / Akses Ditolak**.
5. Coba akses menu petugas: `http://127.0.0.1:8000/petugas/dashboard` &rarr; Dicegah dengan **403 Forbidden** oleh `RoleMiddleware`.

---

## 💡 7. Jawaban Pertanyaan Analisis Kasus

### Pertanyaan 1: Apakah middleware pemeriksaan peran saja cukup untuk mencegah mahasiswa melihat surat milik mahasiswa lain? Jelaskan.
**Jawaban:** **TIDAK CUKUP.**  
Middleware pemeriksaan peran (*Role-Based Access Control*) hanya bekerja pada tingkat perimeter rute (*route level*). Middleware hanya memeriksa pertanyaan: *"Apakah pengguna yang sedang mengakses rute ini berstatus mahasiswa?"*.  
Ketika Mahasiswa A dan Mahasiswa B sama-sama memiliki peran `mahasiswa`, keduanya memiliki izin yang sah untuk melewati middleware peran. Jika sistem hanya bertumpu pada middleware ini, Mahasiswa A dapat mengganti ID pada URL (`/mahasiswa/surat/{id}`) untuk melihat surat milik Mahasiswa B. Celah ini disebut **IDOR (*Insecure Direct Object Reference*)**.  
Untuk mencegahnya, diperlukan lapisan kedua yaitu **pemeriksaan kepemilikan data (*Data Ownership Policy*)** di dalam controller, yang memastikan `surat.user_id === auth()->id()`.

### Pertanyaan 2: Bagaimana membedakan pembatasan akses berdasarkan peran dengan pembatasan berdasarkan kepemilikan data?
**Jawaban:**
- **Pembatasan Berdasarkan Peran (Role-Based):**
  - Bersifat makro/kategori fungsional (*Who can do what*).
  - Contoh: Petugas boleh memproses surat, Mahasiswa tidak boleh.
  - Implementasi: HTTP Middleware pada route group (`role:petugas`, `role:mahasiswa`).
- **Pembatasan Berdasarkan Kepemilikan Data (Ownership-Based):**
  - Bersifat mikro/instansiasi baris data (*Who owns this record*).
  - Contoh: Mahasiswa A dan B sama-sama mahasiswa, tetapi Mahasiswa A hanya boleh melihat surat yang ia buat sendiri (`user_id = 1`).
  - Implementasi: Laravel Policy / Gate (`$user->id === $surat->user_id`).

### Pertanyaan 3: Apa risiko jika pemeriksaan hak akses hanya dilakukan di sisi frontend?
**Jawaban:**
Menaruh proteksi hanya di sisi frontend (misal: menyembunyikan tombol dengan CSS `display:none` atau `v-if`) menimbulkan **risiko keamanan berlevel kritis**:
1. **Manipulasi DOM:** Tombol atau elemen formulir dapat dengan mudah dimunculkan kembali oleh pengguna awam menggunakan menu *Inspect Element* (DevTools) browser.
2. **Eksploitasi Direct HTTP Request:** Pengguna dapat mem-bypass antarmuka sepenuhnya dan mengirimkan *request* langsung melalui cURL, Postman, atau skrip JavaScript di konsol browser ke *endpoint* API/backend.
3. **Pelanggaran Privasi & Kebocoran Data (*Data Breach*):** Data sensitif milik pihak lain dapat dicuri hanya dengan mengganti parameter URL.
4. **Manipulasi Integritas Data:** Penyerang dapat menyetujui suratnya sendiri atau menghapus surat orang lain jika *endpoint* backend tidak memvalidasi hak akses.

> Prinsip dasar rekayasa perangkat lunak yang aman: **"Never trust the client; all authorization must be verified on the server-side."**
