<?php

namespace App\Filament\Resources\GeneralSettingsResource\Pages;

use App\Filament\Resources\GeneralSettingsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListGeneralSettings extends ListRecords
{
    protected static string $resource = GeneralSettingsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
