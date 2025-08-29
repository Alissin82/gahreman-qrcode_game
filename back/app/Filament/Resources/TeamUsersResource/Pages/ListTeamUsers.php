<?php

namespace App\Filament\Resources\TeamUsersResource\Pages;

use App\Filament\Resources\TeamUsersResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTeamUsers extends ListRecords
{
    protected static string $resource = TeamUsersResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
