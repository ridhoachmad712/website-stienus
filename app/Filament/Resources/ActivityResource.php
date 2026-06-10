<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ActivityResource\Pages;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Spatie\Activitylog\Models\Activity;

class ActivityResource extends Resource
{
    protected static ?string $model = Activity::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';

    protected static ?string $navigationGroup = 'Pengguna & Akses';

    protected static ?string $navigationLabel = 'Log Aktivitas';

    protected static ?string $modelLabel = 'Log Aktivitas';

    protected static ?string $pluralModelLabel = 'Log Aktivitas';

    protected static ?int $navigationSort = 2;

    public static function canCreate(): bool
    {
        return false;
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Infolists\Components\TextEntry::make('description')->label('Aksi'),
            Infolists\Components\TextEntry::make('subject_type')->label('Objek')
                ->formatStateUsing(fn (?string $state): string => $state ? class_basename($state) : '-'),
            Infolists\Components\TextEntry::make('causer.name')->label('Oleh')->default('Sistem'),
            Infolists\Components\TextEntry::make('created_at')->label('Waktu')->dateTime('d M Y H:i'),
            Infolists\Components\KeyValueEntry::make('properties')->label('Perubahan')->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('created_at')->label('Waktu')->dateTime('d M Y H:i')->sortable(),
                Tables\Columns\TextColumn::make('causer.name')->label('Oleh')->default('Sistem')->searchable(),
                Tables\Columns\TextColumn::make('description')->label('Aksi')->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'created' => 'success',
                        'updated' => 'warning',
                        'deleted' => 'danger',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('subject_type')->label('Objek')
                    ->formatStateUsing(fn (?string $state): string => $state ? class_basename($state) : '-')
                    ->badge()->color('gray'),
                Tables\Columns\TextColumn::make('subject_id')->label('ID')->toggleable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('description')->label('Aksi')->options([
                    'created' => 'Dibuat',
                    'updated' => 'Diubah',
                    'deleted' => 'Dihapus',
                ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListActivities::route('/'),
        ];
    }
}
