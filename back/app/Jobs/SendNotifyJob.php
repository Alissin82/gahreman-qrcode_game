<?php

namespace App\Jobs;

use App\Models\Notify;
use App\Models\Team;
use Filament\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SendNotifyJob implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    public Notify $notify;

    public function __construct(Notify $notify)
    {
        $this->notify = $notify;
    }

    public function handle()
    {
        if ($this->notify->sms)
            $this->sendSms($this->notify->content);
    }

    public function sendSms(string $content): void
    {
        $smsToken = config('services.ippanel.token');

        $selectedTeams = Team::whereIn('id', $data['teams'] ?? [])->get();
        $phoneNumbers = $selectedTeams->pluck('phone')->filter()->toArray();

        if (count($phoneNumbers) <= 0)
            return;
        $smsData = [
            'sending_type' => 'webservice',
            'from_number' => config('services.ippanel.number'),
            'message' => $content,
            'params' => [
                'recipients' => $phoneNumbers
            ]
        ];

        $this->sendSmsToIPPanel($smsToken, $smsData);
    }

    private function sendSmsToIPPanel(string $token, array $data): ?array
    {
        $baseUrl = config('services.ippanel.base_url');
        $endpoint = $baseUrl . '/api/send';

        try {
            $response = Http::withHeaders([
                'Authorization' => $token,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json'
            ])
                ->timeout(30)
                ->post($endpoint, $data);

            if ($response->successful()) {
                return $response->json();
            } else {
                throw new \Exception('HTTP Error: ' . $response->status() . ' - ' . $response->body());
            }
        } catch (\Exception $e) {
            throw new \Exception('HTTP Request Error: ' . $e->getMessage());
        }

    }
}
