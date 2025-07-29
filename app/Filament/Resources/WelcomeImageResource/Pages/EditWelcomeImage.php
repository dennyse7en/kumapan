<?php

namespace App\Filament\Resources\WelcomeImageResource\Pages;

use App\Filament\Resources\WelcomeImageResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditWelcomeImage extends EditRecord
{
    protected static string $resource = WelcomeImageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
