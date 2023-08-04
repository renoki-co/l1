<?php

namespace RenokiCo\L1;

use Saloon\Http\Connector;

abstract class CloudflareConnector extends Connector
{
    public function __construct(
        protected ?string $token = null,
        public ?string $accountId = null,
        public string $apiUrl = 'https://api.cloudflare.com/client/v4',
    ) {
        $this->withTokenAuth($token);
    }

    public function resolveBaseUrl(): string
    {
        return $this->apiUrl;
    }

    protected function defaultHeaders(): array
    {
        return [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ];
    }
}
