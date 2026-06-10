<?php

namespace App\Filament\Widgets;

use App\Models\Applicant;
use Filament\Widgets\ChartWidget;

class ApplicantsByProgramChart extends ChartWidget
{
    protected static ?string $heading = 'Pendaftar per Program Studi';

    protected static ?int $sort = 3;

    protected function getData(): array
    {
        $counts = Applicant::query()
            ->selectRaw('program, COUNT(*) as total')
            ->groupBy('program')
            ->pluck('total', 'program');

        return [
            'datasets' => [
                [
                    'label' => 'Pendaftar',
                    'data' => $counts->values()->all(),
                    'backgroundColor' => ['#4f46e5', '#10b981', '#f59e0b', '#ef4444', '#6366f1'],
                ],
            ],
            'labels' => $counts->keys()->all(),
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
