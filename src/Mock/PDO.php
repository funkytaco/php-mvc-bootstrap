<?php
namespace Main\Mock;

class PDO extends \PDO
{
    use \Main\Mock\Traits\QueryData;

    public function __construct()
    {
        // Call parent PDO constructor with mock SQLite in-memory database
        parent::__construct('sqlite::memory:', null, null, array(
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION
        ));
    }

    #[\ReturnTypeWillChange]
    public function prepare(string $query, array $options = []): \PDOStatement|false
    {
        // For mocking purposes, we'll return a basic PDOStatement that works with our mock data
        return new class($this) extends \PDOStatement {
            private $pdo;
            
            public function __construct($pdo) {
                $this->pdo = $pdo;
            }

            #[\ReturnTypeWillChange]
            public function execute($params = null): bool
            {
                return true;
            }

            #[\ReturnTypeWillChange]
            public function fetchAll($mode = \PDO::FETCH_DEFAULT, ...$args)
            {
                // Return mock data from the trait
                if (method_exists($this->pdo, 'getUsers')) {
                    return $this->pdo->getUsers();
                }
                return [];
            }
        };
    }
}
