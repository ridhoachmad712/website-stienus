<?php

namespace App\Filament\Resources\DiplomaResource\Pages;

use App\Filament\Resources\DiplomaResource;
use App\Models\Diploma;
use Filament\Resources\Pages\ViewRecord;

class ViewDiploma extends ViewRecord
{
    protected static string $resource = DiplomaResource::class;

    public function getSubheading(): ?string
    {
        return 'Status: '.(Diploma::STATUSES[$this->record->status] ?? $this->record->status);
    }
}
