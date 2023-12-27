<?php

declare(strict_types=1);

namespace Quantum\Database;

use Closure;
use PDO;
use PDOException;
use PDOStatement;
use Throwable;

/**
 * Simple database wrapper class that enables the use of method chaining.
 */
class Database
{
    public const MYSQL_DATETIME = 'Y-m-d H:i:s';

    private PDOStatement $stmt;
    private PDO $pdo;

    public function __construct(
        string $hostname,
        string $database,
        string $charset,
        string $username,
        string $password
    ) {
        $dsn = 'mysql:host=' . $hostname . ';';
        $dsn .= 'dbname=' . $database . ';';
        $dsn .= 'charset=' . $charset . ';';

        try {
            $this->pdo = new PDO($dsn, $username, $password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);
        } catch (PDOException $e) {
            throw new PDOException('PDO connection failed');
        }
    }

    /**
     * Creates a new prepared statement.
     */
    final public function query(string $query): Database
    {
        $this->stmt = $this->pdo->prepare($query);
        return $this;
    }

    /**
     * Binds parameters to a prepared statement.
     */
    final public function bind(mixed $id, mixed $value, int $type = null): Database
    {
        if(is_null($type))
        {
            $type = match (true) {
                is_null($value) => PDO::PARAM_NULL,
                is_int($value) => PDO::PARAM_INT,
                is_bool($value) => PDO::PARAM_BOOL,
                default => PDO::PARAM_STR,
            };
        }

        $this->stmt->bindValue($id, $value, $type);
        return $this;
    }

    /**
     * Executes a prepared statement.
     */
    final public function execute(): bool {
        return $this->stmt->execute();
    }

    /**
     * Returns all result rows.
     */
    final public function fetchAll(int $mode = PDO::FETCH_DEFAULT): array
    {
        $this->execute();
        return $this->stmt->fetchAll($mode);
    }

    /**
     * Selects one result row.
     */
    final public function fetch(int $mode = PDO::FETCH_DEFAULT): array
    {
        $this->execute();
        return $this->stmt->fetch($mode);
    }

    /**
     * Selects one result column.
     */
    final public function fetchColumn(int $position = 0): mixed
    {
        $this->execute();
        return $this->stmt->fetchColumn($position);
    }

    /**
     * Get last inserted id.
     */
    final public function getLastInsertId(): int {
        return (int) $this->pdo->lastInsertId();
    }

    /**
     * Initiates a transaction.
     */
    final public function beginTransaction(): bool {
        return $this->pdo->beginTransaction();
    }

    /**
     * Rolls back a transaction.
     */
    final public function rollBack(): bool {
        return $this->pdo->rollBack();
    }

    /**
     * Commits a transaction.
     */
    final public function commit(): bool {
        return $this->pdo->commit();
    }

    /**
     * Executes an anonymous function within a transaction and rolls back if
     * an exception occurred.
     */
    final public function transaction(Closure $function): mixed
    {
        $this->beginTransaction();

        try {
            $result = $function($this);
            $this->commit();

            return $result;
        } catch (Throwable $exception) {
            $this->rollBack();

            throw new PDOException('Transaction failed');
        }
    }
}
