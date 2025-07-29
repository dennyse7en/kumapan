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
        $applications = Auth::user()->creditApplications()->latest()->get();

        return view('dashboard', ['applications' => $applications]);
    }

    /**
     * Menampilkan formulir untuk membuat pengajuan baru. (F-07)
     */
    public function create()
    {
        return view('credit.create');
    }

    /**
     * Menyimpan pengajuan kredit baru ke database.
     */
    public function store(Request $request)
    {
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