<?php

namespace App\Utils;

class Jwt
{
    private string $secret;
    private int $expirationTime;

    public function __construct(string $secret = 'secret-key', int $expirationTime = 3600)
    {
        $this->secret = $secret;
        $this->expirationTime = $expirationTime;
    }

    public function generateToken(object $userData): string
    {
        $header = json_encode(['typ' => 'JWT', 'alg' => 'HS256'], JSON_UNESCAPED_UNICODE);
        
        $userData->exp = time() + $this->expirationTime;
        $payload = json_encode($userData->toArray(), JSON_UNESCAPED_UNICODE);

        $encodedHeader = $this->base64UrlEncode($header);
        $encodedPayload = $this->base64UrlEncode($payload);

        $signature = $this->signature($encodedHeader, $encodedPayload);

        return $encodedHeader . '.' . $encodedPayload . '.' . $signature;
    }

    private function base64UrlEncode(string $data): string
    {
        return str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($data));
    }

    private function signature(string $header, string $payload): string
    {
        $signature = hash_hmac('sha256', $header . '.' . $payload, $this->secret, true);
        return $this->base64UrlEncode($signature);
    }

    public function setExpirationTime(int $seconds): void
    {
        $this->expirationTime = $seconds;
    }
}