<?php

declare(strict_types=1);

namespace Quantum\Database\Query;

/**
 * Abstract base for query string classes.
 */
abstract class AbstractQuery
{
    protected string $table;

    /**
     * Set the table that will be accessed in that query.
     */
    final public function table(string $table): AbstractQuery {
        $this->table = $table;
        return $this;
    }

    /**
     * Get query object as a string
     */
    abstract public function getQueryString(): string;

    /**
     * Gets called at object to string conversion.
     */
    public function __toString(): string {
        return $this->getQueryString();
    }
}
