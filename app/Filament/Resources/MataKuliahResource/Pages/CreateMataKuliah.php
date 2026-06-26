<?php

namespace App\Filament\Resources\MataKuliahResource\Pages;

use App\Filament\Resources\MataKuliahResource;
use Filament\Resources\Pages\CreateRecord;

class CreateMataKuliah extends CreateRecord
{
    protected static string $resource = MataKuliahResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}