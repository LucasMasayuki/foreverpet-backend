<?php

namespace App\Api\Support\Services;

use App\Api\Support\Contracts\SmsServiceInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TwilioSmsService implements SmsServiceInterface
{
    private string $accountSid;
    private string $authToken;
    private string $fromNumber;
    private bool $enabled;

    public function __construct()
    {
        $this->accountSid = config('services.twilio.account_sid', '');
        $this->authToken = config('services.twilio.auth_token', '');
        $this->fromNumber = config('services.twilio.from_number', '');
        $this->enabled = config('services.twilio.enabled', false);
    }

    public function send(string $phoneNumber, string $message): bool
    {
        if (!$this->enabled) {
            Log::info('SMS not sent (disabled)', [
                'phone' => $phoneNumber,
                'message' => $message,
            ]);
            return true; // Return true in dev mode
        }

        if (empty($this->accountSid) || empty($this->authToken) || empty($this->fromNumber)) {
            Log::error('Twilio credentials not configured');
            return false;
        }

        try {
            $response = Http::withBasicAuth($this->accountSid, $this->authToken)
                ->asForm()
                ->post("https://api.twilio.com/2010-04-01/Accounts/{$this->accountSid}/Messages.json", [
                    'From' => $this->fromNumber,
                    'To' => $phoneNumber,
                    'Body' => $message,
                ]);

            if ($response->successful()) {
                Log::info('SMS sent successfully', [
                    'phone' => $phoneNumber,
                    'sid' => $response->json('sid'),
                ]);
                return true;
            }

            Log::error('Failed to send SMS', [
                'phone' => $phoneNumber,
                'error' => $response->json(),
            ]);
            return false;
        } catch (\Exception $e) {
            Log::error('SMS sending exception', [
                'phone' => $phoneNumber,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    public function sendBulk(array $phoneNumbers, string $message): array
    {
        $results = [];

        foreach ($phoneNumbers as $phoneNumber) {
            $results[$phoneNumber] = $this->send($phoneNumber, $message);
        }

        return $results;
    }

    public function formatPhoneNumber(string $phoneNumber, string $countryCode = '55'): string
    {
        // Remove all non-numeric characters
        $cleaned = preg_replace('/[^0-9]/', '', $phoneNumber);

        // If doesn't start with +, add country code
        if (!str_starts_with($phoneNumber, '+')) {
            // Remove leading zeros
            $cleaned = ltrim($cleaned, '0');

            // Add country code
            $cleaned = '+' . $countryCode . $cleaned;
        }

        return $cleaned;
    }
}


