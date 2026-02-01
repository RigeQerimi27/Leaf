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

   
    public function allWithSummary(): array
    {
        $sql = "
            SELECT
                o.id,
                o.created_by,
                o.status,
                o.subtotal,
                o.created_at,
                COUNT(oi.id) AS items_count
            FROM orders o
            LEFT JOIN order_items oi ON oi.order_id = o.id
            GROUP BY o.id
            ORDER BY o.id DESC
        ";

        $res = $this->connection->query($sql);
        if (!$res) return [];

        return $res->fetch_all(MYSQLI_ASSOC);
    }

   
    public function delete(int $orderId): bool
    {
        $this->connection->begin_transaction();

        try {
          
            $stmt1 = $this->connection->prepare("DELETE FROM order_items WHERE order_id = ?");
            $stmt1->bind_param('i', $orderId);
            $stmt1->execute();
            $stmt1->close();

           
            $stmt2 = $this->connection->prepare("DELETE FROM orders WHERE id = ?");
            $stmt2->bind_param('i', $orderId);
            $stmt2->execute();
            $affected = $stmt2->affected_rows;
            $stmt2->close();

            $this->connection->commit();
            return $affected > 0;
        } catch (Throwable $e) {
            $this->connection->rollback();
            return false;
        }
    }
}
