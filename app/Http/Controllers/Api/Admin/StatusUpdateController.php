<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\CreditApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class StatusUpdateController extends Controller
{
    /**
     * Menangani pembaruan status pengajuan kredit oleh operator/admin.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CreditApplication  $application
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateStatus(Request $request, CreditApplication $application)
    {
        // 1. Validasi Input
        $request->validate([
            'status' => ['required', 'string', Rule::in([
                'Menunggu Verifikasi', 'Sedang Direview', 'Menunggu Persetujuan',
                'Disetujui', 'Ditolak', 'Lunas', 'Dibatalkan'
            ])],
            'notes' => 'nullable|string',
        ]);

        $user = Auth::user();
        $newStatus = $request->status;
        $currentStatus = $application->status;
        $allowed = [];

        // 2. Logika Alur Kerja (Workflow) berdasarkan Peran
        if ($user->hasRole('verifikator')) {
            if ($currentStatus === 'Menunggu Verifikasi') $allowed = ['Sedang Direview', 'Ditolak'];
            if ($currentStatus === 'Disetujui' && $application->approved_at?->lt(Carbon::now()->subDays(30))) $allowed[] = 'Lunas';
        }
        
        if ($user->hasRole('operator')) {
            if ($currentStatus === 'Sedang Direview') $allowed = ['Menunggu Persetujuan', 'Ditolak'];
        }
        
        if ($user->hasRole('approver')) {
            if ($currentStatus === 'Menunggu Persetujuan') $allowed = ['Disetujui', 'Ditolak'];
        }
        
        if ($user->hasRole('Super Admin')) { // Super Admin bisa melakukan apa saja
            $allowed = ['Menunggu Verifikasi', 'Sedang Direview', 'Menunggu Persetujuan', 'Disetujui', 'Ditolak', 'Lunas', 'Dibatalkan'];
        }

        // 3. Cek Otorisasi Alur Kerja
        if (!in_array($newStatus, $allowed)) {
            return response()->json([
                'message' => 'Anda tidak memiliki izin untuk mengubah status dari "' . $currentStatus . '" menjadi "' . $newStatus . '".'
            ], 403); // 403 Forbidden
        }

        // 4. Update Data
        $application->status = $newStatus;
        if ($request->filled('notes')) {
            $application->notes = $request->notes;
        }

        // Catat tanggal persetujuan jika status diubah menjadi "Disetujui"
        if ($newStatus === 'Disetujui' && is_null($application->approved_at)) {
            $application->approved_at = now();
        }
        
        $application->save(); // Menyimpan perubahan akan memicu Observer secara otomatis

        return response()->json([
            'message' => 'Status pengajuan berhasil diubah menjadi "' . $newStatus . '".',
            'application' => $application->fresh('histories') // Kirim kembali data terbaru
        ]);
    }
}