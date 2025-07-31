<?php

namespace App\Filament\Widgets;

use Filament\Tables;
use Filament\Tables\Table;
use App\Models\CreditApplication;
use App\Filament\Resources\CreditApplicationResource;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Tables\Columns\TextColumn;

class LatestCreditApplications extends BaseWidget
{
    protected static ?int $sort = 4;
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                CreditApplication::query()->latest()->limit(5)
            )
            ->columns([
                TextColumn::make('tracking_code')->label('Kode Lacak'),
                TextColumn::make('user.name')->label('Nama Pengaju'),
                TextColumn::make('amount')->money('IDR')->label('Jumlah'),
                TextColumn::make('status')->badge()->color(fn (string $state): string => match ($state) {
                    'Disetujui', 'Lunas' => 'success',
                    'Ditolak', 'Dibatalkan' => 'danger',
                    default => 'warning',
                }),
            ])
            ->actions([
                Tables\Actions\Action::make('Lihat')
                    ->url(fn (CreditApplication $record): string => CreditApplicationResource::getUrl('edit', ['record' => $record])),
            ]);
    }
}