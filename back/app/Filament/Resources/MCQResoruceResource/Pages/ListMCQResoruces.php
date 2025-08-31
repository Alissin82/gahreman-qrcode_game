<?php

namespace App\Filament\Resources\MCQResoruceResource\Pages;

use App\Filament\Resources\MCQResoruceResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMCQResoruces extends ListRecords
{
    protected static string $resource = MCQResoruceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
