<?php

declare(strict_types=1);

namespace Quantum\Database\Query\Syntax;

use function implode;

/**
 * Trait for adding columns to a query.
 */
trait Columns
{
    protected array $columns = [];

    /**
     * Add the column names to the object.
     */
    final public function columns(string ...$columns): self
    {
        $this->columns = $columns;
        return $this;
    }

    /**
     * Get columns to insert as a chained string.
     */
    private function getColumns(): string {
        return implode(', ', $this->columns);
    }
}