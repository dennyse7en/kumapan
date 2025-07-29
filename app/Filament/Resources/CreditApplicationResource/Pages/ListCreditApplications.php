<?php

// app/Filament/Resources/CreditApplicationResource/Pages/ListCreditApplications.php

namespace App\Filament\Resources\CreditApplicationResource\Pages;

use App\Filament\Resources\CreditApplicationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder; // Import class Builder
use Illuminate\Support\Facades\Auth;

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
        $user = Auth::user();
        $tabs = [];

        // Untuk Verifikator
        if ($user->hasRole(['verifikator', 'operator', 'Super Admin'])) {
            $tabs['waiting_verification'] = ListRecords\Tab::make('Menunggu Verifikasi')
                ->modifyQueryUsing(fn(Builder $query) => $query->where('status', 'Menunggu Verifikasi'));
        }

        // Untuk Operator
        if ($user->hasRole(['operator', 'Super Admin'])) {
            $tabs['in_review_operator'] = ListRecords\Tab::make('Perlu Diproses Operator')
                ->modifyQueryUsing(fn(Builder $query) => $query->where('status', 'Sedang Direview'));
        }

        // Untuk Approver dan Operator
        if ($user->hasRole(['approver', 'operator', 'Super Admin'])) {
            $tabs['waiting_approval'] = ListRecords\Tab::make('Menunggu Persetujuan')
                ->modifyQueryUsing(fn(Builder $query) => $query->where('status', 'Menunggu Persetujuan'));
        }

        // Untuk Operator
        if ($user->hasRole(['operator', 'Super Admin'])) {
            $tabs['completed'] = ListRecords\Tab::make('Selesai')
                ->modifyQueryUsing(fn(Builder $query) => $query->whereIn('status', ['Disetujui', 'Ditolak', 'Lunas']));
        }
        // Tab "Semua" selalu tersedia untuk semua peran internal
        $tabs['all'] = ListRecords\Tab::make('Semua');
        return $tabs;
    }
}
