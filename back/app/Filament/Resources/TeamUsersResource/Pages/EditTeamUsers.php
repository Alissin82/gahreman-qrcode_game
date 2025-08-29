<?php

namespace App\Filament\Resources\TeamUsersResource\Pages;

use App\Filament\Resources\TeamUsersResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTeamUsers extends EditRecord
{
    protected static string $resource = TeamUsersResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
