<?php

namespace App\Filament\Resources\FrontendResource\Pages;

use App\Filament\Resources\FrontendResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFrontend extends EditRecord
{
    protected static string $resource = FrontendResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
