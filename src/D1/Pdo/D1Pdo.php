<?php

namespace RenokiCo\L1\D1\Pdo;

use PDO;
use PDOStatement;
use RenokiCo\L1\CloudflareD1Connector;

class D1Pdo extends PDO
{
    protected array $lastInsertIds = [];

    protected bool $inTransaction = false;

    public function __construct(
        protected string $dsn,
        protected CloudflareD1Connector $connector,
    ) {
        parent::__construct('sqlite::memory:');
    }

    public function prepare($query, $options = []): PDOStatement|bool
    {
        return new D1PdoStatement(
            $this,
            $query,
            $options,
        );
    }

    public function d1(): CloudflareD1Connector
    {
        return $this->connector;
    }

    public function setLastInsertId($name = null, $value = null): void
    {
        if ($name === null) {
            $name = 'id';
        }

        $this->lastInsertIds[$name] = $value;
    }

    public function lastInsertId($name = null): bool|string
    {
        if ($name === null) {
            $name = 'id';
        }

        return $this->lastInsertIds[$name] ?? false;
    }

    public function beginTransaction(): bool
    {
        return $this->inTransaction = true;
    }

    public function commit(): bool
    {
        return ! ($this->inTransaction = false);
    }

    public function inTransaction(): bool
    {
        return $this->inTransaction;
    }
}
