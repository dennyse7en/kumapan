<?php

namespace App\Filament\Resources\WelcomeImageResource\Pages;

use App\Filament\Resources\WelcomeImageResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateWelcomeImage extends CreateRecord
{
    protected static string $resource = WelcomeImageResource::class;
}
