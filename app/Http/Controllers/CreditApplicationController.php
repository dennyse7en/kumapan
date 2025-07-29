<?php

namespace App\Http\Controllers;

use App\Models\CreditApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CreditApplicationController extends Controller
{
    /**
     * Display a listing of the user's credit applications.
     */
    public function index()
    {
        // Ambil semua pengajuan kredit milik user yang sedang login, urutkan dari yang terbaru
        $applications = CreditApplication::where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('credit.dashboard', ['applications' => $applications]);
    }

    /**
     * Show the form for creating a new credit application.
     */
    public function create()
    {
        return view('credit.create');
    }

    /**
     * Store a newly created credit application in storage.
     */
    public function store(Request $request)
    {
        // 1. Validasi data input dari form
        $validatedData = $request->validate([
            'ktp_number' => 'required|numeric|digits:16',
            'ktp_photo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'address' => 'required|string|max:1000',
            'phone_number' => 'required|string|max:15',
            'business_name' => 'required|string|max:255',
            'business_type' => 'required|string|max:255',
            'business_age_months' => 'required|integer|min:1',
            'monthly_revenue' => 'required|integer|min:0',
            'business_address' => 'required|string|max:1000',
            'amount_requested' => 'required|integer|min:100000',
            'tenor_months' => 'required|integer',
            'loan_purpose' => 'required|string|max:255',
            'business_photo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'business_document' => 'nullable|file|mimes:pdf|max:2048',
            'bank_statement' => 'nullable|file|mimes:pdf|max:2048',
        ]);

        // 2. Handle file uploads
        $ktpPhotoPath = $request->file('ktp_photo')->store('documents/ktp', 'public');
        $businessPhotoPath = $request->file('business_photo')->store('documents/business_photos', 'public');
        
        $businessDocumentPath = null;
        if ($request->hasFile('business_document')) {
            $businessDocumentPath = $request->file('business_document')->store('documents/business_legal', 'public');
        }

        $bankStatementPath = null;
        if ($request->hasFile('bank_statement')) {
            $bankStatementPath = $request->file('bank_statement')->store('documents/bank_statements', 'public');
        }

        // 3. Buat record baru di database
        CreditApplication::create([
            'user_id' => Auth::id(),
            'submission_id' => 'KRD-' . strtoupper(Str::random(8)),
            'ktp_number' => $validatedData['ktp_number'],
            'ktp_photo_path' => $ktpPhotoPath,
            'address' => $validatedData['address'],
            'phone_number' => $validatedData['phone_number'],
            'business_name' => $validatedData['business_name'],
            'business_type' => $validatedData['business_type'],
            'business_age_months' => $validatedData['business_age_months'],
            'monthly_revenue' => $validatedData['monthly_revenue'],
            'business_address' => $validatedData['business_address'],
            'amount_requested' => $validatedData['amount_requested'],
            'tenor_months' => $validatedData['tenor_months'],
            'loan_purpose' => $validatedData['loan_purpose'],
            'business_photo_path' => $businessPhotoPath,
            'business_document_path' => $businessDocumentPath,
            'bank_statement_path' => $bankStatementPath,
            'status' => 'Menunggu Verifikasi', // Status awal
        ]);

        // 4. Redirect kembali ke dashboard dengan pesan sukses
        return redirect()->route('credit.dashboard')->with('success', 'Pengajuan kredit Anda berhasil dikirim!');
    }
}
