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

        $stmt->execute();
        $newId = (int)$this->connection->insert_id;
        $stmt->close();

        return $newId;
    }
}
