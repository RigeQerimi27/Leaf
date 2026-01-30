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
    ): void {
        $statement = $this->connection->prepare(
            'INSERT INTO shipping_addresses
            (first_name, last_name, phone, street, house_no, zip, state, city, note, created_by)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)'
        );

        $statement->bind_param(
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

        $statement->execute();
        $statement->close();
    }
}
