<?php

namespace App\Api\Support\Services;

use App\Api\Support\Contracts\SmsServiceInterface;
use Illuminate\Support\Facades\Log;

/**
 * Log SMS Service - Para desenvolvimento
 *
 * Apenas loga as mensagens sem enviar de verdade
 */
class LogSmsService implements SmsServiceInterface
{
    public function send(string $phoneNumber, string $message): bool
    {
        Log::info('📱 SMS (LOG MODE)', [
            'to' => $phoneNumber,
            'message' => $message,
            'timestamp' => now()->toIso8601String(),
        ]);

        // Print to console in development
        if (app()->environment('local')) {
            echo "\n";
            echo "📱 ==================== SMS ====================\n";
            echo "To: {$phoneNumber}\n";
            echo "Message: {$message}\n";
            echo "Time: " . now()->format('Y-m-d H:i:s') . "\n";
            echo "===============================================\n";
            echo "\n";
        }

        return true;
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


