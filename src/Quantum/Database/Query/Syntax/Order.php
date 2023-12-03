<?php

declare(strict_types=1);

namespace Quantum\Database\Query\Syntax;

/**
 * Trait for ordering data retrieved by a query string.
 */
trait Order
{
    private const ORDER_SCHEMA = ' ORDER BY ';

    protected ?string $by = null;
    protected ?Direction $order;

    /**
     * Adds an order statement to the query.
     */
    public function order(string $by, ?Direction $order = null): self
    {
        $this->by = $by;
        $this->order = $order;

        return $this;
    }

    /**
     * Return the order query fragment.
     */
    protected function getOrder(): string
    {
        // If no order by fragment was set, return an empty string.
        if ($this->by === null) {
            return '';
        }

        $base = self::ORDER_SCHEMA . $this->by;

        // If a direction is set, add the direction to the end of the statement.
        if ($this->order !== null) {
            $base = $base . ' ' . $this->order->value;
        }

        return $base;
    }
}
