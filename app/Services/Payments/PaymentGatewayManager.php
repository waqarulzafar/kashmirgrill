<?php

namespace App\Services\Payments;

use App\Services\Payments\Contracts\PaymentGateway;
use App\Services\Payments\Exceptions\PaymentException;

class PaymentGatewayManager
{
    /**
     * @var array<string, PaymentGateway>
     */
    private array $gateways = [];

    /**
     * @param  iterable<PaymentGateway>  $gateways
     */
    public function __construct(iterable $gateways)
    {
        foreach ($gateways as $gateway) {
            $this->gateways[$gateway->method()] = $gateway;
        }
    }

    public function for(string $method): PaymentGateway
    {
        if (! isset($this->gateways[$method])) {
            throw new PaymentException("Unsupported payment method [{$method}].");
        }

        return $this->gateways[$method];
    }
}
