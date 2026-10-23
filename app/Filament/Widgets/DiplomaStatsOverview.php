<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\DiplomaResource;
use App\Models\Diploma;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DiplomaStatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected int|string|array $columnSpan = 'full';

    public static function canView(): bool
    {
        return (bool) (auth()->user()?->can('view_any_diploma'));
    }

    protected function getStats(): array
    {
        $issued = Diploma::where('status', 'issued');
        $totalIssued = (clone $issued)->count();
        $manajemen = (clone $issued)->where('program_name', 'like', '%manaj%')->count();
        $akuntansi = $totalIssued - $manajemen;

        $drafts = Diploma::where('status', 'draft')->count();
        $revoked = Diploma::where('status', 'revoked')->count();

        $issuedUrl = DiplomaResource::getUrl('index', ['tableFilters' => ['status' => ['value' => 'issued']]]);

        return [
            Stat::make('Ijazah berlaku', $totalIssued)
                ->description('Sudah terbit QR')
                ->descriptionIcon('heroicon-m-check-badge')
                ->color('success')
                ->url($issuedUrl),

            Stat::make('Akuntansi', $akuntansi)
                ->description('Ijazah berlaku prodi Akuntansi')
                ->descriptionIcon('heroicon-m-academic-cap')
                ->color('primary')
                ->url($issuedUrl),

            Stat::make('Manajemen', $manajemen)
                ->description('Ijazah berlaku prodi Manajemen')
                ->descriptionIcon('heroicon-m-academic-cap')
                ->color('primary')
                ->url($issuedUrl),

            Stat::make('Draf menunggu terbit', $drafts)
                ->description('Periksa arsip lalu terbitkan QR')
                ->descriptionIcon('heroicon-m-pencil-square')
                ->color($drafts > 0 ? 'warning' : 'gray')
                ->url(DiplomaResource::getUrl('index', ['tableFilters' => ['status' => ['value' => 'draft']]])),

            Stat::make('Dicabut', $revoked)
                ->description('Status penerbitan dicabut')
                ->descriptionIcon('heroicon-m-no-symbol')
                ->color($revoked > 0 ? 'danger' : 'gray')
                ->url(DiplomaResource::getUrl('index', ['tableFilters' => ['status' => ['value' => 'revoked']]])),
        ];
    }
}
