<?php

declare(strict_types=1);

namespace Quantum\Database\Query\Syntax;

/**
 * Trait for aggregate functions like sum, count or avg.
 */
trait Aggregates
{
    private const ALIAS_SCHEMA = '%s AS %s';
    private const FUNC_SCHEMA = '%s(%s)';

    protected array $aggregates = [];

    /**
     * Add an aggregate function to the query.
     */
    final public function aggregate(string $column, Functions $with, string $as): self
    {
        // Save aggregate for later.
        $this->aggregates[] = [
            'column' => $column,
            'with' => $with,
            'as' => $as
        ];

        return $this;
    }

    /**
     * Return the aggregate functions as a query string fragment.
     */
    private function getAggregates(): string
    {
        // If no aggregate fragment is defined, return an empty string.
        if (empty($this->aggregates)) {
            return '';
        }

        $columns = [];

        // Create a list of aggregation select fragments.
        foreach ($this->aggregates as $aggregate)
        {
            $column = '`' . $aggregate['column'] . '`';
            $as = '`' . $aggregate['as'] . '`';

            $func = sprintf(
                self::FUNC_SCHEMA,
                $aggregate['with']->value,
                $column
            );

            $columns[] = sprintf(self::ALIAS_SCHEMA, $func, $as);
        }

        return implode(', ', $columns);
    }
}
