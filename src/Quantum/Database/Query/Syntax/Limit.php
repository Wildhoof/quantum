<?php

declare(strict_types=1);

namespace Quantum\Database\Query\Syntax;

use function sprintf;

/**
 * Trait for adding limit to a query string.
 */
trait Limit
{
    private const LIMIT_SCHEMA = ' LIMIT %u, %u';

    protected int $rows = 0;
    protected int $offset = 0;

    /**
     * Adds a limit to the query.
     */
    public function limit(int $rows, int $offset = 0): self
    {
        $this->rows = $rows;
        $this->offset = $offset;

        return $this;
    }

    /**
     * Return the limit query fragment
     */
    protected function getLimit(): string
    {
        // If no limit was set, return an empty string.
        if ($this->rows === 0) {
            return '';
        }

        // Otherwise return entire limit fragment.
        return sprintf(
            self::LIMIT_SCHEMA,
            $this->offset,
            $this->rows
        );
    }
}
