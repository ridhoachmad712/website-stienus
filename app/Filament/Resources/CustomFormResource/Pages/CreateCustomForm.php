<?php

namespace App\Filament\Resources\CustomFormResource\Pages;

use App\Filament\Resources\CustomFormResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateCustomForm extends CreateRecord
{
    protected static string $resource = CustomFormResource::class;
}
