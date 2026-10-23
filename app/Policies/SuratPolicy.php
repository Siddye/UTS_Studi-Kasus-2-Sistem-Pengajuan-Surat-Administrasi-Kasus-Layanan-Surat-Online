<?php

namespace App\Policies;

use App\Models\Surat;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class SuratPolicy
{
    /**
     * Determine whether the user can view the specific surat.
     * Ini adalah kunci keamanan kepemilikan data (Data Ownership Authorization).
     */
    public function view(User $user, Surat $surat): Response
    {
        // Jika pengguna adalah mahasiswa, pastikan user_id pada surat sama persis dengan id mahasiswa yang sedang login
        if ($user->isMahasiswa()) {
            return $user->id === $surat->user_id
                ? Response::allow()
                : Response::deny('Akses Ditolak: Anda tidak memiliki izin untuk melihat pengajuan surat milik mahasiswa lain.');
        }

        // Petugas dan Kepala Bagian diizinkan melihat semua surat dalam lingkup administrasi fakultas
        if ($user->isPetugas() || $user->isKepalaBagian()) {
            return Response::allow();
        }

        return Response::deny('Akses Ditolak: Anda tidak memiliki wewenang untuk melihat data ini.');
    }

    /**
     * Determine whether the user can delete/cancel the surat.
     */
    public function delete(User $user, Surat $surat): Response
    {
        if ($user->isMahasiswa()) {
            if ($user->id !== $surat->user_id) {
                return Response::deny('Anda hanya dapat membatalkan pengajuan milik Anda sendiri.');
            }

            if ($surat->status !== 'menunggu') {
                return Response::deny('Pengajuan surat yang sudah diproses atau disetujui tidak dapat dibatalkan.');
            }

            return Response::allow();
        }

        return Response::deny('Hanya mahasiswa pemilik pengajuan yang dapat membatalkan pengajuan surat ini.');
    }
}
