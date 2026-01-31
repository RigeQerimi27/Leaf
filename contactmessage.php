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
        $stmt = $this->connection->prepare(
            'INSERT INTO contact_messages (name, email, subject, message, created_by)
             VALUES (?, ?, ?, ?, ?)'
        );

       
        if (!$stmt) {
            throw new RuntimeException('Prepare failed: ' . $this->connection->error);
        }

       
        $stmt->bind_param('sssss', $name, $email, $subject, $message, $createdBy);

       
        if (!$stmt->execute()) {
            $stmt->close();
            throw new RuntimeException('Execute failed: ' . $this->connection->error);
        }

        $stmt->close();
    }

   
    public function all(): array
    {
        $result = $this->connection->query(
            'SELECT id, name, email, subject, message, created_by, created_at
             FROM contact_messages
             ORDER BY created_at DESC'
        );

        if (!$result) {
            return [];
        }

        return $result->fetch_all(MYSQLI_ASSOC);
    }

   
    public function find(int $id): ?array
    {
        $stmt = $this->connection->prepare(
            'SELECT id, name, email, subject, message, created_by, created_at
             FROM contact_messages
             WHERE id = ?'
        );

        if (!$stmt) {
            throw new RuntimeException('Prepare failed: ' . $this->connection->error);
        }

        $stmt->bind_param('i', $id);

        if (!$stmt->execute()) {
            $stmt->close();
            throw new RuntimeException('Execute failed: ' . $this->connection->error);
        }

        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        $stmt->close();

        return $row ?: null;
    }

    
    public function delete(int $id): void
    {
        $stmt = $this->connection->prepare(
            'DELETE FROM contact_messages WHERE id = ?'
        );

        if (!$stmt) {
            throw new RuntimeException('Prepare failed: ' . $this->connection->error);
        }

        $stmt->bind_param('i', $id);

        if (!$stmt->execute()) {
            $stmt->close();
            throw new RuntimeException('Execute failed: ' . $this->connection->error);
        }

        $stmt->close();
    }
}

