<?php

namespace App\Filament\Resources\DiplomaResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class EventsRelationManager extends RelationManager
{
    protected static string $relationship = 'events';

    protected static ?string $title = 'Riwayat pemeriksaan dan penerbitan';

    public function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('created_at')->label('Waktu')->dateTime('d M Y H:i:s'),
            Tables\Columns\TextColumn::make('actor_name')->label('Petugas'),
            Tables\Columns\TextColumn::make('action')->label('Tindakan')->formatStateUsing(fn ($state) => [
                'created' => 'Draf dibuat', 'updated' => 'Draf diperbarui', 'issued' => 'QR diterbitkan', 'revoked' => 'Dicabut', 'superseded' => 'Diganti',
            ][$state] ?? $state),
            Tables\Columns\TextColumn::make('reason')->label('Keterangan')->wrap(),
        ])->actions([
            Tables\Actions\Action::make('changes')->label('Lihat perubahan')->modalHeading('Catatan perubahan')->modalSubmitAction(false)->modalCancelActionLabel('Tutup')
                ->modalContent(fn ($record) => view('diplomas.audit', ['event' => $record])),
        ])->defaultSort('id', 'desc');
    }
}
