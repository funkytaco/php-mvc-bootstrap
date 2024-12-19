<?php
namespace Main\Modules;

class PDO_Module extends \PDO
{
    private static $instance = null;
    private const HOST = 'localhost';
    private const PORT = '5432';
    private const DB_NAME = 'icarusdb';
    private const USER = 'icarusadmin';
    private const PASS = '$dddCEOkd40559jdl';

    private function __construct()
    {
        $dsn = sprintf(
            "pgsql:host=%s;port=%s;dbname=%s;user=%s;password=%s",
            self::HOST,
            self::PORT,
            self::DB_NAME,
            self::USER,
            self::PASS
        );

        try {
            parent::__construct($dsn, null, null, [
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
                \PDO::ATTR_EMULATE_PREPARES => false
            ]);
        } catch (\PDOException $e) {
            throw new \Exception("Connection failed: " . $e->getMessage());
        }
    }

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    #[\ReturnTypeWillChange]
    public function prepare(string $query, array $options = []): \PDOStatement|false
    {
        return parent::prepare($query, $options);
    }

    /**
     * Execute a query and fetch all results
     *
     * @param string $query The SQL query
     * @param array $params Parameters to bind
     * @return array The result set
     */
    public function executeQuery(string $query, array $params = []): array
    {
        $stmt = $this->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Execute a query that doesn't return results (INSERT, UPDATE, DELETE)
     *
     * @param string $query The SQL query
     * @param array $params Parameters to bind
     * @return bool Success status
     */
    public function executeStatement(string $query, array $params = []): bool
    {
        $stmt = $this->prepare($query);
        return $stmt->execute($params);
    }

    /**
     * Get a single row from a query
     *
     * @param string $query The SQL query
     * @param array $params Parameters to bind
     * @return array|null The result row or null if no row found
     */
    public function fetchOne(string $query, array $params = []): ?array
    {
        $stmt = $this->prepare($query);
        $stmt->execute($params);
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    /**
     * Begin a transaction
     */
    public function beginTransaction(): bool
    {
        return parent::beginTransaction();
    }

    /**
     * Commit a transaction
     */
    public function commit(): bool
    {
        return parent::commit();
    }

    /**
     * Rollback a transaction
     */
    public function rollBack(): bool
    {
        return parent::rollBack();
    }
}
