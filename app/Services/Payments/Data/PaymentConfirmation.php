<?php

namespace App\Services\Payments\Data;

class PaymentConfirmation
{
    /**
     * @param  array<string, mixed>  $payload
     */
    public function __construct(
        public readonly bool $successful,
        public readonly string $status,
        public readonly ?string $transactionId = null,
        public readonly ?string $sessionId = null,
        public readonly ?string $message = null,
        public readonly array $payload = [],
    ) {}

    /**
     * @param  array<string, mixed>  $payload
     */
    public static function paid(?string $transactionId = null, ?string $sessionId = null, array $payload = []): self
    {
        return new self(true, 'paid', $transactionId, $sessionId, null, $payload);
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    public static function failed(string $message, ?string $transactionId = null, ?string $sessionId = null, array $payload = []): self
    {
        return new self(false, 'failed', $transactionId, $sessionId, $message, $payload);
    }
}
