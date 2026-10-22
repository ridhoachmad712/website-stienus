<?php

namespace App\Filament\Resources\DiplomaResource\Pages;

use App\Filament\Resources\DiplomaResource;
use App\Services\DiplomaService;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditDiploma extends EditRecord
{
    protected static string $resource = DiplomaResource::class;

    public function mount(int|string $record): void
    {
        parent::mount($record);
        abort_unless($this->record->status === 'draft', 403, 'Data ijazah yang telah terbit terkunci.');
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        return app(DiplomaService::class)->updateDraft($record, $data, auth()->user());
    }

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}
