<?php

namespace App\Filament\Widgets;

use App\Models\Announcement;
use App\Models\Applicant;
use App\Models\ContactMessage;
use App\Models\MataKuliah;
use App\Models\Post;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class SiteStatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $publishedPosts = Post::where('status', 'published')->count();
        $draftPosts     = Post::where('status', 'draft')->count();

        $activeAnnouncements = Announcement::whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->count();

        $unreadMessages = ContactMessage::where('is_read', false)->count();
        $totalMessages  = ContactMessage::count();

        $applicantsThisMonth = Applicant::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
        $totalApplicants = Applicant::count();

        return [
            Stat::make('Berita Terbit', $publishedPosts)
                ->description($draftPosts . ' draft menunggu')
                ->descriptionIcon('heroicon-m-newspaper')
                ->color('success')
                ->url(\App\Filament\Resources\PostResource::getUrl()),

            Stat::make('Pengumuman Aktif', $activeAnnouncements)
                ->description('Tampil di halaman publik')
                ->descriptionIcon('heroicon-m-megaphone')
                ->color('info')
                ->url(\App\Filament\Resources\AnnouncementResource::getUrl()),

            Stat::make('Pesan Belum Dibaca', $unreadMessages)
                ->description('dari ' . $totalMessages . ' total pesan')
                ->descriptionIcon('heroicon-m-inbox')
                ->color($unreadMessages > 0 ? 'warning' : 'gray')
                ->url(\App\Filament\Resources\ContactMessageResource::getUrl()),

            Stat::make('Pendaftar Bulan Ini', $applicantsThisMonth)
                ->description($totalApplicants . ' total keseluruhan')
                ->descriptionIcon('heroicon-m-user-plus')
                ->color('primary')
                ->url(\App\Filament\Resources\ApplicantResource::getUrl()),
        ];
    }
}
