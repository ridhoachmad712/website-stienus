<?php

namespace App\Filament\Resources\TranscriptResource\Pages;

use App\Filament\Resources\TranscriptResource;
use App\Models\Transcript;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTranscript extends EditRecord
{
    protected static string $resource = TranscriptResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('print')->label('Cetak transkrip')->icon('heroicon-o-printer')->color('primary')
                ->url(fn (Transcript $record) => route('transcripts.print', $record))->openUrlInNewTab(),
            Actions\DeleteAction::make()->label('Hapus'),
        ];
    }
}
