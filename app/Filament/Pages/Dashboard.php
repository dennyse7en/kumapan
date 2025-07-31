<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;
use App\Filament\Widgets\StatsOverview;
use App\Filament\Widgets\CreditTrendChart;
use App\Filament\Widgets\CreditStatusChart;
use App\Filament\Widgets\LatestCreditApplications;

class Dashboard extends BaseDashboard
{
    /**
     * Mendefinisikan widget yang akan ditampilkan di dashboard.
     * Array ini akan menimpa widget default dari Filament.
     *
     * @return array<class-string>
     */
    public function getWidgets(): array
    {
        return [
            StatsOverview::class,
            CreditTrendChart::class,
            CreditStatusChart::class,
            LatestCreditApplications::class,
        ];
    }
}