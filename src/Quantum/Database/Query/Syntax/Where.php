<?php

declare(strict_types=1);

namespace Quantum\Database\Query\Syntax;

use function implode;
use function sprintf;

/**
 * Trait for adding where conditions to a query.
 */
trait Where
{
    private const WHERE_SCHEMA = ' WHERE %s';
    private const WHERE_IS = '`%s` = :%s';
    private const WHERE_NOT = '`%s` != :%s';
    private const WHERE_SMALLER = '`%s` < :%s';
    private const WHERE_GREATER = '`%s` > :%s';

    protected array $conditions = [];

    /**
     * Add column and placeholder of an equal condition to where.
     */
    public function where(string $column, string $placeholder = null): self
    {
        $this->conditions[] = sprintf(
            self::WHERE_IS,
            $column,
            $placeholder ?? $column
        );

        return $this;
    }

    /**
     * Add column and placeholder for a not like condition to where.
     */
    public function whereNot(string $column, string $placeholder = null): self
    {
        $this->conditions[] = sprintf(
            self::WHERE_NOT,
            $column,
            $placeholder ?? $column
        );

        return $this;
    }

    /**
     * Add a column and placeholder for a greater than condition to where.
     */
    public function whereGreater(
        string $column,
        string $placeholder = null
    ): self
    {
        $this->conditions[] = sprintf(
            self::WHERE_GREATER,
            $column,
            $placeholder ?? $column
        );

        return $this;
    }

    /**
     * Add a column and placeholder for a smaller than condition to where.
     */
    public function whereSmaller(
        string $column,
        string $placeholder = null
    ): self
    {
        $this->conditions[] = sprintf(
            self::WHERE_SMALLER,
            $column,
            $placeholder ?? $column
        );

        return $this;
    }

    /**
     * Get entire where string part.
     */
    protected function getWhere(): string
    {
        // If no where is present, just return empty string.
        if (empty($this->conditions)) {
            return '';
        }

        // Otherwise return entire where fragment.
        return sprintf(
            self::WHERE_SCHEMA,
            implode(' AND ', $this->conditions)
        );
    }
}
