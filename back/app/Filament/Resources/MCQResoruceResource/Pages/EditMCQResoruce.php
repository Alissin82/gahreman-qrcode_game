<?php

namespace App\Filament\Resources\MCQResoruceResource\Pages;

use App\Filament\Resources\MCQResoruceResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMCQResoruce extends EditRecord
{
    protected static string $resource = MCQResoruceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
