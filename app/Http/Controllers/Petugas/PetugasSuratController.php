<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Surat;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class PetugasSuratController extends Controller
{
    /**
     * Dashboard Petugas Administrasi: statistik dan antrean permohonan surat masuk.
     */
    public function dashboard(): View
    {
        $stats = [
            'total' => Surat::count(),
            'menunggu' => Surat::where('status', 'menunggu')->count(),
            'diproses' => Surat::where('status', 'diproses')->count(),
            'disetujui' => Surat::where('status', 'disetujui')->count(),
            'ditolak' => Surat::where('status', 'ditolak')->count(),
        ];

        $antreanTerbaru = Surat::with('mahasiswa')
            ->whereIn('status', ['menunggu', 'diproses'])
            ->latest()
            ->take(6)
            ->get();

        return view('petugas.dashboard', compact('stats', 'antreanTerbaru'));
    }

    /**
     * Menampilkan semua permohonan surat masuk di fakultas dengan filter status dan pencarian.
     */
    public function index(Request $request): View
    {
        $query = Surat::with(['mahasiswa', 'petugas', 'kepalaBagian'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nomor_surat', 'like', "%{$search}%")
                  ->orWhere('jenis_surat', 'like', "%{$search}%")
                  ->orWhere('keperluan', 'like', "%{$search}%")
                  ->orWhereHas('mahasiswa', function ($m) use ($search) {
                      $m->where('name', 'like', "%{$search}%")
                        ->orWhere('nim', 'like', "%{$search}%");
                  });
            });
        }

        $surats = $query->paginate(10)->withQueryString();

        return view('petugas.surat.index', compact('surats'));
    }

    /**
     * Menampilkan detail surat untuk verifikasi dan pemrosesan administrasi.
     */
    public function show(Surat $surat): View
    {
        Gate::authorize('view', $surat);

        $surat->load(['mahasiswa', 'petugas', 'kepalaBagian']);

        return view('petugas.surat.show', compact('surat'));
    }

    /**
     * Memproses permohonan surat (verifikasi berkas, masukkan nomor surat, dan perbarui catatan).
     */
    public function proses(Request $request, Surat $surat): RedirectResponse
    {
        $validated = $request->validate([
            'nomor_surat' => ['nullable', 'string', 'max:100'],
            'catatan_petugas' => ['required', 'string', 'min:5', 'max:1000'],
            'status' => ['required', 'in:diproses,ditolak'],
        ], [
            'catatan_petugas.required' => 'Catatan verifikasi dari petugas wajib diisi.',
            'status.in' => 'Status proses tidak valid.',
        ]);

        $surat->update([
            'status' => $validated['status'],
            'nomor_surat' => $validated['nomor_surat'] ?: $surat->nomor_surat,
            'catatan_petugas' => $validated['catatan_petugas'],
            'petugas_id' => auth()->id(),
            'tanggal_diproses' => now(),
        ]);

        $pesan = $validated['status'] === 'diproses'
            ? 'Permohonan surat berhasil diproses dan diverifikasi! Siap ditindaklanjuti.'
            : 'Permohonan surat telah ditolak oleh petugas dengan alasan terlampir.';

        return redirect()->route('petugas.surat.show', $surat)->with('success', $pesan);
    }
}
