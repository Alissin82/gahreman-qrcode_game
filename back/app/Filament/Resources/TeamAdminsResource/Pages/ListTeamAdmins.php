<?php

namespace App\Filament\Resources\TeamAdminsResource\Pages;

use App\Filament\Resources\TeamAdminsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTeamAdmins extends ListRecords
{
    protected static string $resource = TeamAdminsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
