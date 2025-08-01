<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Rules\ValidNik; 
use App\Models\CreditApplication;

class CreditApplicationApiController extends Controller
{
    /**
     * Mengambil riwayat pengajuan kredit milik pengguna.
     */
    public function index()
    {
        $applications = Auth::user()->creditApplications()->latest()->get();
        return response()->json($applications);
    }

    /**
     * Menyimpan pengajuan kredit baru.
     */
    public function store(Request $request)
    {
        // Cek apakah ada pengajuan aktif
        $activeApplicationExists = Auth::user()->creditApplications()
            ->whereNotIn('status', ['Ditolak', 'Lunas', 'Dibatalkan'])
            ->exists();

        if ($activeApplicationExists) {
            return response()->json(['message' => 'Anda sudah memiliki pengajuan yang aktif.'], 422);
        }

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
            'ktp_path' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'business_photo_path' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $validated['ktp_path'] = $request->file('ktp_path')->store('ktp_images', 'public');
        $validated['business_photo_path'] = $request->file('business_photo_path')->store('business_photos', 'public');

        $application = Auth::user()->creditApplications()->create($validated);

        return response()->json($application, 201);
    }

    /**
     * Mengambil detail satu pengajuan kredit.
     */
    public function show($id)
    {
        $application = Auth::user()
                           ->creditApplications()
                           ->with('histories')
                           ->findOrFail($id);

        return response()->json($application);
    }
}