<?php

namespace App\Filament\Widgets;

use App\Models\CreditApplication;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Number;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        // 1. Total Pengajuan Masuk (30 hari terakhir)
        $totalApplications = CreditApplication::where('created_at', '>=', now()->subDays(30))->count();

        // 2. Total Pinjaman Disetujui (Rp)
        $totalApprovedAmount = CreditApplication::where('status', 'Disetujui')->sum('amount');

        // 3. Pengajuan dalam Proses
        $applicationsInProcess = CreditApplication::whereIn('status', [
            'Menunggu Verifikasi', 'Sedang Direview', 'Menunggu Persetujuan'
        ])->count();

        // 4. Tingkat Persetujuan
        $totalFinished = CreditApplication::whereIn('status', ['Disetujui', 'Ditolak'])->count();
        $totalApproved = CreditApplication::where('status', 'Disetujui')->count();
        $approvalRate = $totalFinished > 0 ? ($totalApproved / $totalFinished) * 100 : 0;

        return [
            Stat::make('Total Pengajuan (30 Hari)', $totalApplications)
                ->description('Jumlah pengajuan baru')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),
            Stat::make('Total Pinjaman Disetujui', 'Rp ' . Number::format($totalApprovedAmount, 0, 0, 'id'))
                ->description('Akumulasi dana yang disetujui')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),
            Stat::make('Pengajuan Dalam Proses', $applicationsInProcess)
                ->description('Pengajuan yang aktif diproses')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),
            Stat::make('Tingkat Persetujuan', number_format($approvalRate, 2) . '%')
                ->description('Dari total pengajuan selesai')
                ->descriptionIcon('heroicon-m-check-badge')
                ->color('success'),
        ];
    }
}