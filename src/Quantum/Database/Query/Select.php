<?php

declare(strict_types=1);

namespace Quantum\Database\Query;

use Quantum\Database\Query\Syntax\Aggregates;
use Quantum\Database\Query\Syntax\Columns;
use Quantum\Database\Query\Syntax\Group;
use Quantum\Database\Query\Syntax\Limit;
use Quantum\Database\Query\Syntax\Order;
use Quantum\Database\Query\Syntax\Where;

use function sprintf;

/**
 * Class for building a select query string.
 */
class Select extends AbstractQuery
{
    use Columns;
    use Aggregates;
    use Where;
    use Limit;
    use Group;
    use Order;

    private const SCHEMA = 'SELECT %s FROM `%s`';

    /**
     * Get query object as a string
     */
    public function getQueryString(): string
    {
        $columns = $this->getColumns();
        $aggregates = $this->getAggregates();

        // Build the column snippet with columns and aggregates combined.
        if (!empty($columns) && !empty($aggregates)) {
            $snippet = $columns . ', ' . $aggregates;
        } else {
            $snippet = $columns . $aggregates;
        }

        // Build full query string.
        $sql = sprintf(
            self::SCHEMA,
            $snippet,
            $this->table
        ) . $this->getWhere() . $this->getGroup();

        return $sql . $this->getOrder() . $this->getLimit();
    }
}
