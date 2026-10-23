<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Surat;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class SuratController extends Controller
{
    /**
     * Dashboard mahasiswa: ringkasan statistik status surat milik mahasiswa.
     */
    public function dashboard(): View
    {
        $user = auth()->user();

        $stats = [
            'total' => $user->surats()->count(),
            'menunggu' => $user->surats()->where('status', 'menunggu')->count(),
            'diproses' => $user->surats()->where('status', 'diproses')->count(),
            'disetujui' => $user->surats()->where('status', 'disetujui')->count(),
            'ditolak' => $user->surats()->where('status', 'ditolak')->count(),
        ];

        $recentSurats = $user->surats()
            ->latest()
            ->take(5)
            ->get();

        return view('mahasiswa.dashboard', compact('stats', 'recentSurats'));
    }

    /**
     * Daftar surat yang diajukan oleh mahasiswa yang sedang login.
     */
    public function index(Request $request): View
    {
        $query = auth()->user()->surats()->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('jenis_surat', 'like', '%' . $request->search . '%')
                  ->orWhere('nomor_surat', 'like', '%' . $request->search . '%')
                  ->orWhere('keperluan', 'like', '%' . $request->search . '%');
            });
        }

        $surats = $query->paginate(10)->withQueryString();

        return view('mahasiswa.surat.index', compact('surats'));
    }

    /**
     * Tampilkan form pengajuan surat baru.
     */
    public function create(): View
    {
        $jenisSurat = Surat::JENIS_SURAT;

        return view('mahasiswa.surat.create', compact('jenisSurat'));
    }

    /**
     * Simpan pengajuan surat baru.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'jenis_surat' => ['required', 'string', 'max:255'],
            'keperluan' => ['required', 'string', 'min:5', 'max:1000'],
            'keterangan_tambahan' => ['nullable', 'string', 'max:1000'],
            'lampiran' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:2048'],
        ], [
            'jenis_surat.required' => 'Pilih jenis permohonan surat.',
            'keperluan.required' => 'Tuliskan keperluan pengajuan surat secara jelas.',
            'keperluan.min' => 'Keperluan permohonan minimal 5 karakter.',
            'lampiran.mimes' => 'Format file lampiran harus PDF, JPG, atau PNG.',
            'lampiran.max' => 'Ukuran file lampiran maksimal 2MB.',
        ]);

        $lampiranPath = null;
        if ($request->hasFile('lampiran')) {
            $lampiranPath = $request->file('lampiran')->store('lampiran_surat', 'public');
        }

        $surat = auth()->user()->surats()->create([
            'jenis_surat' => $validated['jenis_surat'],
            'keperluan' => $validated['keperluan'],
            'keterangan_tambahan' => $validated['keterangan_tambahan'] ?? null,
            'lampiran' => $lampiranPath,
            'status' => 'menunggu',
        ]);

        return redirect()->route('mahasiswa.surat.show', $surat)
            ->with('success', 'Pengajuan surat berhasil dikirim! Silakan pantau status permohonan Anda secara berkala.');
    }

    /**
     * Detail surat mahasiswa.
     * TITIK KRUSIAL: Memeriksa kepemilikan data melalui SuratPolicy.
     * Jika mahasiswa mencoba mengakses surat mahasiswa lain via URL, Gate akan memblokir dengan 403 Forbidden.
     */
    public function show(Surat $surat): View
    {
        // Pemeriksaan hak akses / kepemilikan data
        Gate::authorize('view', $surat);

        return view('mahasiswa.surat.show', compact('surat'));
    }

    /**
     * Batalkan pengajuan surat jika status masih menunggu.
     */
    public function destroy(Surat $surat): RedirectResponse
    {
        Gate::authorize('delete', $surat);

        if ($surat->lampiran) {
            Storage::disk('public')->delete($surat->lampiran);
        }

        $surat->delete();

        return redirect()->route('mahasiswa.surat.index')
            ->with('success', 'Pengajuan surat berhasil dibatalkan dan dihapus.');
    }
}
