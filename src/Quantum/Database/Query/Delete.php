<?php

declare(strict_types=1);

namespace Quantum\Database\Query;

use Quantum\Database\Query\Syntax\Where;

use function sprintf;

/**
 * Class for building a "delete query" string.
 */
class Delete extends AbstractQuery
{
    use Where;

    private const SCHEMA = 'DELETE FROM `%s`';

    /**
     * Get query object as a string
     */
    public function getQueryString(): string
    {
        return sprintf(
            self::SCHEMA,
            $this->table
        ) . $this->getWhere();
    }
}
