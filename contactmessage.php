<?php

declare(strict_types=1);


class ContactMessage
{
   
    private mysqli $connection;

    
    public function __construct(mysqli $connection)
    {
        $this->connection = $connection;
    }

    public function create(
        string $name,
        string $email,
        string $subject,
        string $message,
        string $createdBy
    ): void {
        

        $statement = $this->connection->prepare(
            'INSERT INTO contact_messages (name, email, subject, message, created_by)
             VALUES (?, ?, ?, ?, ?)'
        );

        
        $statement->bind_param(
            'sssss',
            $name,
            $email,
            $subject,
            $message,
            $createdBy
        );

        
        $statement->execute();

       
        $statement->close();
    }

    
    public function all(): array
    {
       
        $result = $this->connection->query(
            'SELECT * FROM contact_messages ORDER BY created_at DESC'
        );

        
        if (!$result) {
            return [];
        }

       
        return $result->fetch_all(MYSQLI_ASSOC);
    }

   
    public function find(int $id): ?array
    {
        
        $statement = $this->connection->prepare(
            'SELECT * FROM contact_messages WHERE id = ?'
        );

       
        $statement->bind_param('i', $id);

        
        $statement->execute();

       
        $result = $statement->get_result();

       
        $message = $result->fetch_assoc();

        
        $statement->close();

        
        return $message ?: null;
    }

    
    public function delete(int $id): void
    {
        $statement = $this->connection->prepare(
            'DELETE FROM contact_messages WHERE id = ?'
        );

        $statement->bind_param('i', $id);

        $statement->execute();
        $statement->close();
    }
}
