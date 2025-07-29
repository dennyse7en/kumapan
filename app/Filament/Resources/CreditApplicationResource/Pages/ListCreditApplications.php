<?php

namespace App\Filament\Resources\CreditApplicationResource\Pages;

use App\Filament\Resources\CreditApplicationResource;
use App\Models\CreditApplication;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\ListRecords\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListCreditApplications extends ListRecords
{
protected static string $resource = CreditApplicationResource::class;

protected function getActions(): array
{
    return [
        // Tombol "New" tidak diperlukan di sini karena pengajuan dibuat oleh pengguna
        // Actions\CreateAction::make(), 
    ];
}

/**
 * Mendefinisikan Tabs untuk filtering.
 */
public function getTabs(): array
{
    return [
        'all' => Tab::make('Semua Pengajuan')
            ->modifyQueryUsing(fn (Builder $query) => $query),

        'Menunggu Verifikasi' => Tab::make('Menunggu Verifikasi')
            ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'Menunggu Verifikasi'))
            ->badge(CreditApplication::where('status', 'Menunggu Verifikasi')->count()),

        'Sedang Direview' => Tab::make('Perlu Diproses Operator')
            ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'Sedang Direview'))
            ->badge(CreditApplication::where('status', 'Sedang Direview')->count()),

        'Menunggu Persetujuan' => Tab::make('Menunggu Persetujuan')
            ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'Menunggu Persetujuan'))
            ->badge(CreditApplication::where('status', 'Menunggu Persetujuan')->count()),

        'completed' => Tab::make('Selesai')
            ->modifyQueryUsing(fn (Builder $query) => $query->whereIn('status', ['Disetujui', 'Ditolak'])),
    ];
}
}
