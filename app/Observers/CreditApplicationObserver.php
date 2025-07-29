<?php

namespace App\Observers;

use App\Models\CreditApplication;
use App\Models\CreditApplicationHistory;
use Illuminate\Support\Facades\Auth;

class CreditApplicationObserver
{
    /**
     * Handle the CreditApplication "created" event.
     */
    public function created(CreditApplication $creditApplication): void
    {
        //
    }

    /**
     * Handle the CreditApplication "updated" event.
     */
    public function updated(CreditApplication $creditApplication): void
    {
        // Cek apakah kolom 'status' benar-benar berubah
        if ($creditApplication->isDirty('status')) {
            CreditApplicationHistory::create([
                'credit_application_id' => $creditApplication->id,
                'user_id' => Auth::id(), // Ambil ID user yang sedang login
                'status' => $creditApplication->status, // Ambil status yang BARU
                'notes' => $creditApplication->getOriginal('notes'), // Ambil catatan dari state SEBELUM diubah
            ]);
        }
    }

    /**
     * Handle the CreditApplication "deleted" event.
     */
    public function deleted(CreditApplication $creditApplication): void
    {
        //
    }

    /**
     * Handle the CreditApplication "restored" event.
     */
    public function restored(CreditApplication $creditApplication): void
    {
        //
    }

    /**
     * Handle the CreditApplication "force deleted" event.
     */
    public function forceDeleted(CreditApplication $creditApplication): void
    {
        //
    }
}
