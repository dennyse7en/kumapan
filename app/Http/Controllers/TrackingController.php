<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CreditApplication;

class TrackingController extends Controller
{
    public function showForm()
    {
        return view('tracking.form');
    }

    public function showStatus(Request $request)
    {
        $request->validate(['tracking_code' => 'required|string|size:6']);

        $application = CreditApplication::where('tracking_code', $request->tracking_code)
                                        ->with('histories') // Eager load relasi history
                                        ->first();

        if (!$application) {
            return redirect()->route('track.form')->with('error', 'Kode pelacakan tidak ditemukan.');
        }

        return view('tracking.status', ['application' => $application]);
    }
}