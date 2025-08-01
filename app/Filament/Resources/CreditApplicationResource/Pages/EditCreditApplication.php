<?php

namespace App\Filament\Resources\CreditApplicationResource\Pages;

use App\Filament\Resources\CreditApplicationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCreditApplication extends EditRecord
{
    protected static string $resource = CreditApplicationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\DeleteAction::make(),
        ];
    }
}
