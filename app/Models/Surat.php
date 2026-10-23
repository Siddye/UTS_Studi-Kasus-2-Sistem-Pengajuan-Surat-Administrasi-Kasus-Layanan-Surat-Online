<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Surat extends Model
{
    use HasFactory;

    protected $fillable = [
        'nomor_surat',
        'user_id',
        'jenis_surat',
        'keperluan',
        'keterangan_tambahan',
        'lampiran',
        'status',
        'catatan_petugas',
        'catatan_kepala_bagian',
        'petugas_id',
        'kepala_bagian_id',
        'tanggal_diproses',
        'tanggal_selesai',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_diproses' => 'datetime',
            'tanggal_selesai' => 'datetime',
        ];
    }

    public const JENIS_SURAT = [
        'Surat Keterangan Aktif Kuliah' => 'Surat Keterangan Aktif Kuliah',
        'Surat Izin Penelitian' => 'Surat Izin Penelitian / Tugas Akhir',
        'Surat Pengantar Magang / PKL' => 'Surat Pengantar Magang / PKL',
        'Surat Keterangan Berkelakuan Baik' => 'Surat Keterangan Berkelakuan Baik',
        'Surat Permohonan Cuti Akademik' => 'Surat Permohonan Cuti Akademik',
    ];

    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function petugas(): BelongsTo
    {
        return $this->belongsTo(User::class, 'petugas_id');
    }

    public function kepalaBagian(): BelongsTo
    {
        return $this->belongsTo(User::class, 'kepala_bagian_id');
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'menunggu' => 'Menunggu Verifikasi',
            'diproses' => 'Sedang Diproses',
            'disetujui' => 'Disetujui',
            'ditolak' => 'Ditolak',
            default => ucfirst($this->status),
        };
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'menunggu' => 'bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-300 border border-amber-300 dark:border-amber-700',
            'diproses' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/40 dark:text-blue-300 border border-blue-300 dark:border-blue-700',
            'disetujui' => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-300 border border-emerald-300 dark:border-emerald-700',
            'ditolak' => 'bg-rose-100 text-rose-800 dark:bg-rose-900/40 dark:text-rose-300 border border-rose-300 dark:border-rose-700',
            default => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300',
        };
    }
}
