<?php

namespace App\Filament\Resources\TranscriptResource\Pages;

use App\Filament\Resources\TranscriptResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTranscript extends CreateRecord
{
    protected static string $resource = TranscriptResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['created_by'] = auth()->id();

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        // Arahkan ke halaman edit agar petugas langsung mengisi mata kuliah.
        return $this->getResource()::getUrl('edit', ['record' => $this->getRecord()]);
    }
}
