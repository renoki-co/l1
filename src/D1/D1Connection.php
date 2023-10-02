<?php

namespace RenokiCo\L1\D1;

use Illuminate\Database\SQLiteConnection;
use RenokiCo\L1\CloudflareD1Connector;
use RenokiCo\L1\D1\Pdo\D1Pdo;

class D1Connection extends SQLiteConnection
{
    public function __construct(
        protected CloudflareD1Connector $connector,
        protected $config = [],
    ) {
        parent::__construct(
            new D1Pdo('sqlite::memory:', $this->connector),
            $config['database'] ?? '',
            $config['prefix'] ?? '',
            $config,
        );
    }

    protected function getDefaultSchemaGrammar()
    {
        ($grammar = new D1SchemaGrammar)->setConnection($this);

        return $this->withTablePrefix($grammar);
    }

    public function d1(): CloudflareD1Connector
    {
        return $this->connector;
    }
}
