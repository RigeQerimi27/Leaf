<?php


declare(strict_types=1);


class HomepageContent
{
    
    private mysqli $connection;

    public function __construct(mysqli $connection)
    {
        
        $this->connection = $connection;
    }

    public function latest(): ?array
    {
        
        $result = $this->connection->query(
            'SELECT * FROM homepage_content ORDER BY id DESC LIMIT 1'
        );

       
        if (!$result) {
            return null;
        }

        
        $row = $result->fetch_assoc();

        
        return $row ?: null;
    }
}
