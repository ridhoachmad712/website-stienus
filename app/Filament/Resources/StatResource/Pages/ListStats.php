<?php

namespace App\Filament\Resources\StatResource\Pages;

use App\Filament\Resources\StatResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListStats extends ListRecords
{
    protected static string $resource = StatResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
