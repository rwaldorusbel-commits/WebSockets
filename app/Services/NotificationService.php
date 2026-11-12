<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Twilio\Rest\Client;

class NotificationService
{
    public function send(string $phone, string $message): void
    {
        $to  = $this->normalize($phone);
        $cfg = config('services.twilio');

        if (! $cfg['sid'] || ! $cfg['token']) {
            Log::info("[Notificación simulada] {$to}: {$message}");
            return;
        }

        $prefix = $cfg['channel'] === 'whatsapp' ? 'whatsapp:' : '';
        $from   = $cfg['channel'] === 'whatsapp' && ! str_starts_with($cfg['from'], 'whatsapp:')
            ? 'whatsapp:'.$cfg['from']
            : $cfg['from'];

        (new Client($cfg['sid'], $cfg['token']))->messages->create($prefix.$to, [
            'from' => $from,
            'body' => $message,
        ]);
    }

    /** Convierte 987654321 en +51987654321 */
    private function normalize(string $phone): string
    {
        $digits = preg_replace('/\D/', '', $phone);

        if (strlen($digits) === 9) {
            $digits = '51'.$digits;
        }

        return '+'.$digits;
    }
}
