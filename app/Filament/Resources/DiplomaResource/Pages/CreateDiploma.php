<?php

namespace App\Filament\Resources\DiplomaResource\Pages;

use App\Filament\Resources\DiplomaResource;
use App\Services\DiplomaService;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateDiploma extends CreateRecord
{
    protected static string $resource = DiplomaResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        return app(DiplomaService::class)->createDraft($data, auth()->user());
    }

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}
