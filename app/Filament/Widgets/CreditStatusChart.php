<?php

namespace App\Filament\Widgets;

use App\Models\CreditApplication;
use Filament\Widgets\ChartWidget;

class CreditStatusChart extends ChartWidget
{
    protected static ?string $heading = 'Komposisi Status Pengajuan';
    protected static ?int $sort = 3;

    protected function getData(): array
    {
        $data = CreditApplication::select('status', \DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        return [
            'datasets' => [
                [
                    'label' => 'Pengajuan',
                    'data' => $data->values(),
                    'backgroundColor' => [
                        '#FFCE56', // Menunggu Verifikasi (kuning)
                        '#36A2EB', // Sedang Direview (biru)
                        '#4BC0C0', // Menunggu Persetujuan (tosca)
                        '#4CAF50', // Disetujui (hijau)
                        '#F44336', // Ditolak (merah)
                        '#2E7D32', // Lunas (hijau tua)
                        '#9E9E9E', // Dibatalkan (abu-abu)
                    ],
                ],
            ],
            'labels' => $data->keys(),
        ];
    }

    protected function getType(): string
    {
        return 'pie'; // Tipe grafik pie
    }
}