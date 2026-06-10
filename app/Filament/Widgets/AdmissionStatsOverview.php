<?php

namespace App\Filament\Widgets;

use App\Models\Applicant;
use App\Models\ContactMessage;
use App\Models\Lecturer;
use App\Models\Post;
use App\Models\Program;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AdmissionStatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        return [
            Stat::make('Total Pendaftar', Applicant::count())
                ->description(Applicant::where('status', 'pending')->count().' menunggu ditindaklanjuti')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning')
                ->chart($this->applicantsTrend()),
            Stat::make('Pesan Belum Dibaca', ContactMessage::where('is_read', false)->count())
                ->description('dari '.ContactMessage::count().' total pesan')
                ->descriptionIcon('heroicon-m-inbox')
                ->color('info'),
            Stat::make('Berita Terbit', Post::where('status', 'published')->count())
                ->description('Dosen: '.Lecturer::count().' • Prodi: '.Program::count())
                ->descriptionIcon('heroicon-m-newspaper')
                ->color('success'),
        ];
    }

    /**
     * Applicant counts for the last 7 days (mini sparkline).
     *
     * @return array<int, int>
     */
    private function applicantsTrend(): array
    {
        return collect(range(6, 0))
            ->map(fn (int $daysAgo): int => Applicant::whereDate('created_at', now()->subDays($daysAgo))->count())
            ->all();
    }
}
