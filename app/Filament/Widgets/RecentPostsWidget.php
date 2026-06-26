<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\PostResource;
use App\Models\Post;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class RecentPostsWidget extends BaseWidget
{
    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = 2;

    protected static ?string $heading = 'Berita Terbaru';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Post::query()->latest()->limit(8)
            )
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Judul')
                    ->limit(55)
                    ->searchable()
                    ->url(fn (Post $record): string => PostResource::getUrl('edit', ['record' => $record])),

                Tables\Columns\TextColumn::make('category.name')
                    ->label('Kategori')
                    ->badge()
                    ->color('gray'),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'published' => 'success',
                        'draft'     => 'warning',
                        default     => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'published' => 'Terbit',
                        'draft'     => 'Draft',
                        default     => $state,
                    }),

                Tables\Columns\TextColumn::make('published_at')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->placeholder('—')
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\Action::make('edit')
                    ->icon('heroicon-m-pencil-square')
                    ->url(fn (Post $record): string => PostResource::getUrl('edit', ['record' => $record])),
            ])
            ->headerActions([
                Tables\Actions\Action::make('lihat_semua')
                    ->label('Lihat Semua')
                    ->icon('heroicon-m-arrow-right')
                    ->url(PostResource::getUrl())
                    ->color('gray'),
                Tables\Actions\Action::make('tulis')
                    ->label('Tulis Berita')
                    ->icon('heroicon-m-plus')
                    ->url(PostResource::getUrl('create')),
            ])
            ->paginated(false);
    }
}
