<?php

namespace App\Http\Responses;

use App\Filament\Resources\CreditApplicationResource;
use Illuminate\Http\RedirectResponse;
use Livewire\Features\SupportRedirects\Redirector;

class LoginResponse extends \Filament\Http\Responses\Auth\LoginResponse
{
    /**
     * Membuat respons HTTP yang mewakili objek.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function toResponse($request): RedirectResponse|Redirector
    {
        // Langsung arahkan ke halaman index dari CreditApplicationResource
        return redirect()->to(CreditApplicationResource::getUrl('index'));
    }
}