<?php

namespace App\Filament\Resources\CustomFormResource\RelationManagers;

use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class SubmissionsRelationManager extends RelationManager
{
    protected static string $relationship = 'submissions';

    protected static ?string $title = 'Kiriman';

    protected static ?string $icon = 'heroicon-o-inbox-stack';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('created_at')->label('Waktu')->dateTime('d M Y H:i')->sortable(),
                Tables\Columns\TextColumn::make('data')->label('Ringkasan')
                    ->formatStateUsing(fn ($state): string => collect(is_array($state) ? $state : [])
                        ->map(fn ($v, $k) => $k.': '.(is_array($v) ? implode(', ', $v) : $v))
                        ->take(2)->implode(' · '))
                    ->wrap()->limit(80),
            ])
            ->headerActions([])
            ->actions([
                Tables\Actions\ViewAction::make()->label('Lihat'),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Infolists\Components\KeyValueEntry::make('data')->label('Isian')->columnSpanFull(),
            Infolists\Components\TextEntry::make('created_at')->label('Dikirim')->dateTime('d M Y H:i'),
        ]);
    }

    public function isReadOnly(): bool
    {
        return false;
    }

    public function canCreate(): bool
    {
        return false;
    }
}
