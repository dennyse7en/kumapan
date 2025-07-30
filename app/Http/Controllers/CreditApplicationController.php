<?php

// app/Http/Controllers/CreditApplicationController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\CreditApplication;
use App\Rules\ValidNik; // Import ValidNik rule

class CreditApplicationController extends Controller
{
    // Definisikan status selesai di satu tempat agar mudah diubah
    private $finishedStatuses = ['Ditolak', 'Lunas', 'Dibatalkan'];

    /**
     * Menampilkan dashboard dengan riwayat pengajuan kredit. (F-06)
     */
    public function index()
    {
        $user = Auth::user();
        $applications = Auth::user()->creditApplications()->latest()->get();

        $activeApplicationExists = $user->creditApplications()
            ->whereNotIn('status', $this->finishedStatuses) // Status yang dianggap selesai
            ->exists();

        return view('dashboard', [
            'applications' => $applications,
            'canApply' => !$activeApplicationExists // Kirim status bisa mengajukan ke view
        ]);
    }

    /**
     * Menampilkan formulir untuk membuat pengajuan baru. (F-07)
     */
    public function create()
    {
        $activeApplicationExists = Auth::user()->creditApplications()
            ->whereNotIn('status', $this->finishedStatuses)
            ->exists();

        if ($activeApplicationExists) {
            // Jika masih ada pengajuan aktif, kembalikan ke dashboard dengan pesan error
            return redirect()->route('dashboard')->with('error', 'Anda tidak dapat membuat pengajuan baru karena masih ada pengajuan yang aktif.');
        }

        return view('credit.create');
    }

    /**
     * Menyimpan pengajuan kredit baru ke database.
     */
    public function store(Request $request)
    {
        $activeApplicationExists = Auth::user()->creditApplications()
            ->whereNotIn('status', $this->finishedStatuses)
            ->exists();

        if ($activeApplicationExists) {
            return redirect()->route('dashboard')->with('error', 'Gagal menyimpan. Anda sudah memiliki pengajuan yang aktif.');
        }
        // Validasi input
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'nik' => ['required', 'string', 'digits:16', 'unique:credit_applications', new ValidNik],
            'phone_number' => 'required|string|max:15',
            'address' => 'required|string',
            'business_name' => 'required|string|max:255',
            'business_address' => 'required|string',
            'business_type' => 'required|string|max:255',
            'amount' => 'required|numeric|min:100000',
            'tenor' => 'required|integer|in:6,12,18,24',
            'ktp_path' => 'required|image|mimes:jpeg,png,jpg|max:2048', // F-07: Upload KTP
            'business_photo_path' => 'required|image|mimes:jpeg,png,jpg|max:2048', // F-07: Upload Foto Usaha
        ]);

        // Proses upload file
        $validated['ktp_path'] = $request->file('ktp_path')->store('ktp_images', 'public');
        $validated['business_photo_path'] = $request->file('business_photo_path')->store('business_photos', 'public');

        // Simpan data menggunakan relasi
        Auth::user()->creditApplications()->create($validated);

        return redirect()->route('dashboard')->with('success', 'Pengajuan kredit Anda berhasil dikirim!');
    }

    public function show(CreditApplication $application)
    {
        // Pastikan user hanya bisa melihat pengajuannya sendiri
        if (Auth::id() !== $application->user_id) {
            abort(403);
        }

        // Eager load relasi history
        $application->load('histories');

        return view('credit.show', ['application' => $application]);
    }

    /**
     * Membatalkan pengajuan kredit.
     */
    public function cancel(Request $request, CreditApplication $application)
    {
        // 1. Pastikan user hanya bisa membatalkan miliknya sendiri
        if (Auth::id() !== $application->user_id) {
            abort(403);
        }

        // 2. Validasi status
        if (in_array($application->status, ['Disetujui', 'Ditolak', 'Lunas', 'Dibatalkan'])) {
            return back()->with('error', 'Pengajuan ini tidak dapat dibatalkan.');
        }

        // 3. Update status dan catatan
        $application->status = 'Dibatalkan';
        $application->notes = $request->input('cancellation_reason', 'Dibatalkan oleh pengguna.');
        $application->save();

        return redirect()->route('dashboard')->with('success', 'Pengajuan berhasil dibatalkan.');
    }
}
