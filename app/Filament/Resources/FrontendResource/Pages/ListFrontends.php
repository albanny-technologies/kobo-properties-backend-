<?php

namespace App\Filament\Resources\FrontendResource\Pages;

use App\Filament\Resources\FrontendResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFrontends extends ListRecords
{
    protected static string $resource = FrontendResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
