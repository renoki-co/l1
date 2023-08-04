<?php

namespace RenokiCo\L1;

use Saloon\Http\Request;
use Saloon\Contracts\Connector;

abstract class CloudflareRequest extends Request
{
    protected CloudflareConnector $connector;

    public function __construct($connector)
    {
        $this->connector = $connector;
    }

    protected function resolveConnector(): Connector
    {
        return $this->connector;
    }
}
