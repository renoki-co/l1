<?php

namespace RenokiCo\L1\D1\Pdo;

use Illuminate\Support\Arr;
use PDO;
use PDOException;
use PDOStatement;

class D1PdoStatement extends PDOStatement
{
    protected int $fetchMode = PDO::FETCH_ASSOC;
    protected array $bindings = [];
    protected array $responses = [];

    public function __construct(
        protected D1Pdo &$pdo,
        protected string $query,
        protected array $options = [],
    ) {
        //
    }

    public function setFetchMode(int $mode, mixed ...$args): bool
    {
        $this->fetchMode = $mode;

        return true;
    }

    public function bindValue($param, $value, $type = PDO::PARAM_STR): bool
    {
        $this->bindings[$param] = match ($type) {
            PDO::PARAM_STR => (string) $value,
            PDO::PARAM_BOOL => (bool) $value,
            PDO::PARAM_INT => (int) $value,
            PDO::PARAM_NULL => null,
            default => $value,
        };

        return true;
    }

    public function execute($params = []): bool
    {
        $this->bindings = array_values($this->bindings ?: $params);

        $response = $this->pdo->d1()->databaseQuery(
            $this->query,
            $this->bindings,
        );

        if ($response->failed() || ! $response->json('success')) {
            throw new PDOException(
                (string) $response->json('errors.0.message'),
                (int) $response->json('errors.0.code'),
            );
        }

        $this->responses = $response->json('result');

        $lastId = Arr::get(Arr::last($this->responses), 'meta.last_row_id', null);

        if (! in_array($lastId, [0, null])) {
            $this->pdo->setLastInsertId(value: $lastId);
        }

        return true;
    }

    public function fetchAll(int $mode = PDO::FETCH_DEFAULT, ...$args): array
    {
        $response = match ($this->fetchMode) {
            PDO::FETCH_ASSOC => $this->rowsFromResponses(),
            PDO::FETCH_OBJ => collect($this->rowsFromResponses())->map(function ($row) {
                return (object) $row;
            })->toArray(),
            default => throw new PDOException('Unsupported fetch mode.'),
        };

        return $response;
    }

    public function rowCount(): int
    {
        return count($this->rowsFromResponses());
    }

    protected function rowsFromResponses(): array
    {
        return collect($this->responses)
            ->map(fn ($response) => $response['results'])
            ->collapse()
            ->toArray();
    }
}
