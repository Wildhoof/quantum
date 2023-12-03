<?php

declare(strict_types=1);

namespace Quantum\Database\Query\Syntax;

/**
 * Trait containing the available math operations.
 */
enum MathOperator : string
{
    case ADD = '+';
    case SUBTRACT = '-';
    case MULTIPLY = '*';
    case DIVIDE = '/';
    case MODULO = '%';
}