<?php


declare(strict_types=1);


class AboutContent
{
    
    private mysqli $connection;

    public function __construct(mysqli $connection)
    {
        
        $this->connection = $connection;
    }

    public function latest(): ?array
    {
        
        $result = $this->connection->query(
            'SELECT * FROM about_content ORDER BY id DESC LIMIT 1'
        );

        
        if (!$result) {
            return null;
        }

        
        $row = $result->fetch_assoc();

        
        return $row ?: null;
    }
}
