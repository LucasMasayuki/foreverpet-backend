<?php

namespace App\Api\Support\Contracts;

interface SmsServiceInterface
{
    /**
     * Send SMS message
     *
     * @param string $phoneNumber Phone number with country code
     * @param string $message Message to send
     * @return bool Success status
     */
    public function send(string $phoneNumber, string $message): bool;

    /**
     * Send SMS to multiple numbers
     *
     * @param array $phoneNumbers Array of phone numbers
     * @param string $message Message to send
     * @return array Results for each number
     */
    public function sendBulk(array $phoneNumbers, string $message): array;

    /**
     * Format phone number
     *
     * @param string $phoneNumber
     * @param string $countryCode
     * @return string Formatted phone number
     */
    public function formatPhoneNumber(string $phoneNumber, string $countryCode = '55'): string;
}


