<?php

declare(strict_types=1);

namespace Quantum\Database\Query\Syntax;

/**
 * Trait containing the order by direction keywords.
 */
enum Direction : string
{
    case ASC = 'ASC';
    case DESC = 'DESC';
}
