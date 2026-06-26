<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\ApplicantResource;
use App\Filament\Resources\ContactMessageResource;
use App\Models\ContactMessage;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class RecentInboxWidget extends BaseWidget
{
    protected static ?int $sort = 3;

    protected int|string|array $columnSpan = 'full';

    protected static ?string $heading = 'Pesan Masuk Terbaru';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                ContactMessage::query()->latest()->limit(8)
            )
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Pengirim')
                    ->weight(fn (ContactMessage $record) => $record->is_read ? null : 'bold')
                    ->limit(25),

                Tables\Columns\TextColumn::make('subject')
                    ->label('Subjek')
                    ->limit(30)
                    ->placeholder('—'),

                Tables\Columns\IconColumn::make('is_read')
                    ->label('')
                    ->icon(fn (bool $state): string => $state ? 'heroicon-m-envelope-open' : 'heroicon-m-envelope')
                    ->color(fn (bool $state): string => $state ? 'gray' : 'warning'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Waktu')
                    ->since()
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\Action::make('lihat')
                    ->icon('heroicon-m-eye')
                    ->url(fn (ContactMessage $record): string => ContactMessageResource::getUrl('edit', ['record' => $record])),
            ])
            ->headerActions([
                Tables\Actions\Action::make('pendaftar')
                    ->label('Pendaftar')
                    ->icon('heroicon-m-user-group')
                    ->url(ApplicantResource::getUrl())
                    ->color('gray'),
                Tables\Actions\Action::make('semua_pesan')
                    ->label('Semua Pesan')
                    ->icon('heroicon-m-inbox')
                    ->url(ContactMessageResource::getUrl())
                    ->color('gray'),
            ])
            ->paginated(false);
    }
}
