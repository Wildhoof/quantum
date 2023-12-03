<?php

declare(strict_types=1);

namespace Quantum\Database\Query\Syntax;

/**
 * Trait for grouping data retrieved by a query string.
 */
trait Group
{
    private const GROUP_SCHEMA = ' GROUP BY ';

    protected ?string $group = null;

    /**
     * Adds an order statement to the query.
     */
    public function group(string $by): self
    {
        $this->group = $by;
        return $this;
    }

    /**
     * Return the group by query fragment.
     */
    protected function getGroup(): string
    {
        // If no group by fragment was set, return an empty string.
        if ($this->group === null) {
            return '';
        }

        return self::GROUP_SCHEMA . $this->group;
    }
}