<?php

namespace App\Http\Controllers\KepalaBagian;

use App\Http\Controllers\Controller;
use App\Models\Surat;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class KepalaBagianSuratController extends Controller
{
    /**
     * Dashboard Kepala Bagian: ringkasan permohonan yang menunggu persetujuan resmi.
     */
    public function dashboard(): View
    {
        $stats = [
            'total' => Surat::count(),
            'perlu_persetujuan' => Surat::whereIn('status', ['diproses', 'menunggu'])->count(),
            'disetujui' => Surat::where('status', 'disetujui')->count(),
            'ditolak' => Surat::where('status', 'ditolak')->count(),
        ];

        $antreanPersetujuan = Surat::with(['mahasiswa', 'petugas'])
            ->where('status', 'diproses')
            ->latest('tanggal_diproses')
            ->take(5)
            ->get();

        return view('kepala_bagian.dashboard', compact('stats', 'antreanPersetujuan'));
    }

    /**
     * Daftar permohonan surat di tingkat fakultas.
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

        return view('kepala_bagian.surat.index', compact('surats'));
    }

    /**
     * Menampilkan detail surat untuk evaluasi persetujuan oleh Kepala Bagian.
     */
    public function show(Surat $surat): View
    {
        Gate::authorize('view', $surat);

        $surat->load(['mahasiswa', 'petugas', 'kepalaBagian']);

        // Generate saran nomor surat jika belum ada
        $suggestedNomorSurat = $surat->nomor_surat ?: sprintf(
            '%03d/FT-AKD/UNIV/%s/%d',
            $surat->id,
            date('m'),
            date('Y')
        );

        return view('kepala_bagian.surat.show', compact('surat', 'suggestedNomorSurat'));
    }

    /**
     * Menyetujui permohonan surat secara resmi.
     */
    public function setujui(Request $request, Surat $surat): RedirectResponse
    {
        $validated = $request->validate([
            'nomor_surat' => ['required', 'string', 'max:100'],
            'catatan_kepala_bagian' => ['nullable', 'string', 'max:1000'],
        ], [
            'nomor_surat.required' => 'Nomor surat resmi wajib dicantumkan saat persetujuan.',
        ]);

        $surat->update([
            'status' => 'disetujui',
            'nomor_surat' => $validated['nomor_surat'],
            'catatan_kepala_bagian' => $validated['catatan_kepala_bagian'] ?? 'Permohonan surat telah diverifikasi dan disetujui resmi oleh Kepala Bagian.',
            'kepala_bagian_id' => auth()->id(),
            'tanggal_selesai' => now(),
        ]);

        return redirect()->route('kepala-bagian.surat.show', $surat)
            ->with('success', 'Permohonan surat berhasil DISETUJUI secara resmi!');
    }

    /**
     * Menolak permohonan surat.
     */
    public function tolak(Request $request, Surat $surat): RedirectResponse
    {
        $validated = $request->validate([
            'catatan_kepala_bagian' => ['required', 'string', 'min:5', 'max:1000'],
        ], [
            'catatan_kepala_bagian.required' => 'Alasan penolakan permohonan wajib diisi secara transparan.',
        ]);

        $surat->update([
            'status' => 'ditolak',
            'catatan_kepala_bagian' => $validated['catatan_kepala_bagian'],
            'kepala_bagian_id' => auth()->id(),
            'tanggal_selesai' => now(),
        ]);

        return redirect()->route('kepala-bagian.surat.show', $surat)
            ->with('success', 'Permohonan surat telah DITOLAK.');
    }
}
