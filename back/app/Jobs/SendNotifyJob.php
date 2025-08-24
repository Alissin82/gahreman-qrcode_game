<?php

namespace App\Jobs;

use App\Models\Notify;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendNotifyJob implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    public $notify;

    public function __construct(Notify $notify)
    {
        $this->notify = $notify;
    }

    public function handle()
    {
        // Example logic
    }
}
