<?php
declare(strict_types=1);


class ShippingAddress
{
    private mysqli $connection;

    public function __construct(mysqli $connection)
    {
        $this->connection = $connection;
    }

    
    public function create(
        string $firstName,
        string $lastName,
        string $phone,
        string $street,
        string $houseNo,
        string $zip,
        ?string $state,
        string $city,
        ?string $note,
        string $createdBy
    ): int {
        $stmt = $this->connection->prepare(
            'INSERT INTO shipping_addresses
            (first_name, last_name, phone, street, house_no, zip, state, city, note, created_by)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)'
        );

        if (!$stmt) {
            throw new RuntimeException('Prepare failed: ' . $this->connection->error);
        }

        $stmt->bind_param(
            'ssssssssss',
            $firstName,
            $lastName,
            $phone,
            $street,
            $houseNo,
            $zip,
            $state,
            $city,
            $note,
            $createdBy
        );

        if (!$stmt->execute()) {
            $stmt->close();
            throw new RuntimeException('Execute failed: ' . $this->connection->error);
        }

        $newId = (int)$this->connection->insert_id;
        $stmt->close();

        return $newId;
    }

    
    public function all(): array
    {
        
        $result = $this->connection->query(
            "SELECT *
             FROM shipping_addresses
             ORDER BY id DESC"
        );

        if (!$result) {
            return [];
        }

        return $result->fetch_all(MYSQLI_ASSOC);
    }

   
    public function find(int $id): ?array
    {
        $stmt = $this->connection->prepare(
            "SELECT * FROM shipping_addresses WHERE id = ?"
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
            "DELETE FROM shipping_addresses WHERE id = ?"
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

