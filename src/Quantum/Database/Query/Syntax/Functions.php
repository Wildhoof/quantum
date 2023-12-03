<?php

declare(strict_types=1);

namespace Quantum\Database\Query\Syntax;

/**
 * Trait containing the aggregate function names.
 */
enum Functions : string
{
    case AVG = 'AVG';
    case COUNT = 'COUNT';
    case MAX = 'MAX';
    case MIN = 'MIN';
    case SUM = 'SUM';
}
