<?php

namespace App\Filament\Resources\StatResource\Pages;

use App\Filament\Resources\StatResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditStat extends EditRecord
{
    protected static string $resource = StatResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
