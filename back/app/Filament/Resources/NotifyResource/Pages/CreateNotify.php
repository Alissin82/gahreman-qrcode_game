<?php

namespace App\Filament\Resources\NotifyResource\Pages;

use App\Filament\Resources\NotifyResource;
use App\Jobs\SendNotifyJob;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Log;

class CreateNotify extends CreateRecord
{
    protected static string $resource = NotifyResource::class;

    protected function afterCreate($record): void
    {
        if ($record->sms) {
            Log::error('dispatching notify job');
            SendNotifyJob::dispatch($record);
        }

    }
}
