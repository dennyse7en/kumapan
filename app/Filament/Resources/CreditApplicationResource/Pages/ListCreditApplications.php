<?php

// app/Filament/Resources/CreditApplicationResource/Pages/ListCreditApplications.php

namespace App\Filament\Resources\CreditApplicationResource\Pages;

use App\Filament\Resources\CreditApplicationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder; // Import class Builder

class ListCreditApplications extends ListRecords
{
    protected static string $resource = CreditApplicationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Kita tidak ingin admin membuat pengajuan baru dari sini
            // Actions\CreateAction::make(),
        ];
    }
    
    // TAMBAHKAN METHOD INI
    public function getTabs(): array
    {
        return [
            'all' => ListRecords\Tab::make('Semua'),
            'waiting_verification' => ListRecords\Tab::make('Menunggu Verifikasi') // F-09
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'Menunggu Verifikasi')),
            'in_review_operator' => ListRecords\Tab::make('Perlu Diproses Operator') // F-10
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'Sedang Direview')),
            'waiting_approval' => ListRecords\Tab::make('Menunggu Persetujuan') // F-11
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'Menunggu Persetujuan')),
            'completed' => ListRecords\Tab::make('Selesai')
                ->modifyQueryUsing(fn (Builder $query) => $query->whereIn('status', ['Disetujui', 'Ditolak'])),
        ];
    }
}