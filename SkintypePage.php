<?php
declare(strict_types=1);

class SkintypePage
{
    
    private mysqli $conn;

    
    public function __construct(mysqli $conn)
    {
        $this->conn = $conn;
    }

    
    public function latest(): array
    {
        
        $result = $this->conn->query("SELECT * FROM skintype_page ORDER BY id DESC LIMIT 1");

        
        if (!$result) {
            return [];
        }

      
        $row = $result->fetch_assoc();

        
        return $row ?: [];
    }
}
