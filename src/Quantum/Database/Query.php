<?php

declare(strict_types=1);

namespace Quantum\Database;

use Quantum\Database\Query\Select;
use Quantum\Database\Query\Insert;
use Quantum\Database\Query\Update;
use Quantum\Database\Query\Delete;

/**
 * Class for building a query string.
 */
class Query
{
    /**
     * Creates a new select statement.
     */
    public function select(): Select {
        return new Select();
    }

    /**
     * Creates a new insert statement.
     */
    public function insert(): Insert {
        return new Insert();
    }

    /**
     * Creates a new update statement.
     */
    public function update(): Update {
        return new Update();
    }

    /**
     * Creates a new delete statement.
     */
    public function delete(): Delete {
        return new Delete();
    }
}
