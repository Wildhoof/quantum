<?php

declare(strict_types=1);

namespace Quantum\Database\Query;

use Quantum\Database\Query\Syntax\MathOperator;
use Quantum\Database\Query\Syntax\Where;

use function implode;
use function sprintf;

/**
 * Class for building an update query string.
 */
class Update extends AbstractQuery
{
    use Where;

    private const SCHEMA = 'UPDATE `%s` SET %s';
    private const SET_CALC = '`%s` = `%s` %s :%s';

    private array $updates = [];
    private array $calc = [];

    /**
     * Adds the values to update
     */
    public function set(string ...$columns): Update
    {
        $this->updates = $columns;
        return $this;
    }

    /**
     * Set a calculated value.
     */
    public function calc(string $column, MathOperator $operator): Update
    {
        $this->calc[$column] = $operator;
        return $this;
    }

    /**
     * Get the fragment to be inserted after SET.
     */
    private function getUpdates(): string
    {
        $updates = [];

        foreach ($this->updates as $column)
        {
            $updates[] = sprintf(
                self::WHERE_IS,
                $column, $column
            );
        }

        foreach ($this->calc as $column => $operator)
        {
            $updates[] = sprintf(
                self::SET_CALC,
                $column, $column, $operator->value, $column
            );
        }

        return implode(', ', $updates);
    }

    /**
     * Get query object as a string
     */
    public function getQueryString(): string
    {
        return sprintf(
            self::SCHEMA,
            $this->table,
            $this->getUpdates()
        ) . $this->getWhere();
    }
}
