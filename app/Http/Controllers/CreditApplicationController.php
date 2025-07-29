<?php

// app/Http/Controllers/CreditApplicationController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CreditApplicationController extends Controller
{
    /**
     * Menampilkan dashboard dengan riwayat pengajuan kredit. (F-06)
     */
    public function index()
    {
        $user = Auth::user();
        $applications = Auth::user()->creditApplications()->latest()->get();

        $activeApplicationExists = $user->creditApplications()
            ->whereNotIn('status', ['Ditolak', 'Lunas']) // Status yang dianggap selesai
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
            ->whereNotIn('status', ['Ditolak', 'Lunas'])
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
            ->whereNotIn('status', ['Ditolak', 'Lunas'])
            ->exists();

        if ($activeApplicationExists) {
            return redirect()->route('dashboard')->with('error', 'Gagal menyimpan. Anda sudah memiliki pengajuan yang aktif.');
        }
        // Validasi input
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'nik' => 'required|string|digits:16|unique:credit_applications',
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

        return redirect()->route('create.dashboard')->with('success', 'Pengajuan kredit Anda berhasil dikirim!');
    }
}
