<?php
declare(strict_types=1);

class Order
{
    private mysqli $connection;

    public function __construct(mysqli $connection)
    {
        $this->connection = $connection;
    }

    public function create(string $createdBy, int $shippingAddressId, float $subtotal): int
    {
        $stmt = $this->connection->prepare(
            "INSERT INTO orders (created_by, shipping_address_id, status, subtotal)
             VALUES (?, ?, 'pending', ?)"
        );

        $stmt->bind_param('sid', $createdBy, $shippingAddressId, $subtotal);
        $stmt->execute();

        $orderId = (int)$this->connection->insert_id;
        $stmt->close();

        return $orderId;
    }

    public function addItem(int $orderId, int $productId, int $qty, float $unitPrice): void
    {
        $stmt = $this->connection->prepare(
            "INSERT INTO order_items (order_id, product_id, qty, unit_price)
             VALUES (?, ?, ?, ?)"
        );

        $stmt->bind_param('iiid', $orderId, $productId, $qty, $unitPrice);
        $stmt->execute();
        $stmt->close();
    }
}
