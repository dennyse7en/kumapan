<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CreditApplication;

class TrackingApiController extends Controller
{
    /**
     * Menangani permintaan pelacakan status berdasarkan tracking_code.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function track(Request $request)
    {
        $request->validate([
            'tracking_code' => 'required|string|size:6',
        ]);

        $application = CreditApplication::where('tracking_code', $request->tracking_code)
                                        ->with('histories') // Ambil juga riwayatnya
                                        ->first();

        if (!$application) {
            return response()->json(['message' => 'Kode pelacakan tidak ditemukan.'], 404);
        }

        // Kita hanya akan menampilkan data yang relevan untuk publik
        return response()->json([
            'tracking_code' => $application->tracking_code,
            'status' => $application->status,
            'histories' => $application->histories,
        ]);
    }
}