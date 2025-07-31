<?php

namespace App\Filament\Widgets;

use App\Models\CreditApplication;
use Filament\Widgets\ChartWidget;
use Carbon\Carbon;

class CreditTrendChart extends ChartWidget
{
    protected static ?string $heading = 'Grafik Tren Pengajuan Kredit (30 Hari Terakhir)';
    protected static ?int $sort = 2; // Urutan widget di dashboard

    protected function getData(): array
    {
        $data = CreditApplication::select('created_at')
            ->where('created_at', '>=', now()->subDays(30))
            ->get()
            ->groupBy(function($date) {
                return Carbon::parse($date->created_at)->format('d M'); // Group per hari
            })
            ->map(function ($item) {
                return count($item);
            });

        return [
            'datasets' => [
                [
                    'label' => 'Pengajuan Baru',
                    'data' => $data->values(),
                    'backgroundColor' => 'rgba(54, 162, 235, 0.2)',
                    'borderColor' => 'rgb(54, 162, 235)',
                ],
            ],
            'labels' => $data->keys(),
        ];
    }

    protected function getType(): string
    {
        return 'line'; // Tipe grafik garis
    }
}