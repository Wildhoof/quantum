<?php

declare(strict_types=1);

namespace Quantum\Database\Query;

use Quantum\Database\Query\Syntax\Columns;

use function implode;
use function sprintf;

/**
 * Class for building an insert query string.
 */
class Insert extends AbstractQuery
{
    use Columns;

    private const SCHEMA = 'INSERT INTO `%s` (%s) VALUES (%s)';

    /**
     * Get values as a chained string.
     */
    private function getValues(): string
    {
        $placeholders = [];

        // Create a list of placeholders
        foreach ($this->columns as $column) {
            $placeholders[] = ':' . $column;
        }

        return implode(', ', $placeholders);
    }

    /**
     * Get query object as a string
     */
    public function getQueryString(): string
    {
        return sprintf(
            self::SCHEMA,
            $this->table,
            $this->getColumns(),
            $this->getValues()
        );
    }
}
