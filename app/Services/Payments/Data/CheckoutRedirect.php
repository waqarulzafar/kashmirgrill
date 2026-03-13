<?php

namespace App\Services\Payments\Data;

class CheckoutRedirect
{
    /**
     * @param  array<string, mixed>  $meta
     */
    public function __construct(
        public readonly string $method,
        public readonly string $url,
        public readonly ?string $sessionId = null,
        public readonly ?string $reference = null,
        public readonly array $meta = [],
    ) {}
}
