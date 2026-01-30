<?php
declare(strict_types=1);

class Database
{
    private string $host;
    private string $user;
    private string $password;
    private string $database;
    private ?mysqli $connection = null;

    public function __construct()
    {
        $this->host = getenv('DB_HOST') ?: '127.0.0.1';
        $this->user = getenv('DB_USER') ?: 'root';
        $this->password = getenv('DB_PASSWORD') ?: '';
        $this->database = getenv('DB_NAME') ?: 'projekti_web';
    }

    public function getConnection(): mysqli
    {
        if ($this->connection instanceof mysqli) {
            return $this->connection;
        }

        $this->connection = new mysqli(
            $this->host,
            $this->user,
            $this->password,
            $this->database
        );

        if ($this->connection->connect_error) {
            throw new RuntimeException('Database connection failed: ' . $this->connection->connect_error);
        }

        $this->connection->set_charset('utf8mb4');
        return $this->connection;
    }
}
