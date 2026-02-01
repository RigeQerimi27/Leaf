<?php
declare(strict_types=1);

class Product
{
    private mysqli $connection;

    public function __construct(mysqli $connection)
    {
        $this->connection = $connection;
    }

    public function allByPage(string $shopPage): array
    {
        $stmt = $this->connection->prepare(
            'SELECT * FROM products WHERE shop_page = ? ORDER BY created_at DESC'
        );

        $stmt->bind_param('s', $shopPage);
        $stmt->execute();

        $res = $stmt->get_result();
        $rows = $res ? $res->fetch_all(MYSQLI_ASSOC) : [];

        $stmt->close();
        return $rows;
    }

    public function allByPageAndSection(string $shopPage, string $shopSection): array
    {
        $stmt = $this->connection->prepare(
            'SELECT * FROM products WHERE shop_page = ? AND shop_section = ? ORDER BY created_at DESC'
        );

        $stmt->bind_param('ss', $shopPage, $shopSection);
        $stmt->execute();

        $res = $stmt->get_result();
        $rows = $res ? $res->fetch_all(MYSQLI_ASSOC) : [];

        $stmt->close();
        return $rows;
    }

    
    public function searchByPageAndSection(string $shopPage, string $shopSection, string $query): array
    {
       
        $q = '%' . $query . '%';

        $stmt = $this->connection->prepare(
            "SELECT *
             FROM products
             WHERE shop_page = ?
               AND shop_section = ?
               AND (
                    name LIKE ?
                    OR benefit LIKE ?
                    OR size LIKE ?
                    OR description LIKE ?
               )
             ORDER BY created_at DESC"
        );

        
        $stmt->bind_param('ssssss', $shopPage, $shopSection, $q, $q, $q, $q);
        $stmt->execute();

        $res = $stmt->get_result();
        $rows = $res ? $res->fetch_all(MYSQLI_ASSOC) : [];

        $stmt->close();
        return $rows;
    }

    public function all(): array
    {
        $res = $this->connection->query('SELECT * FROM products ORDER BY created_at DESC');
        if (!$res) return [];
        return $res->fetch_all(MYSQLI_ASSOC);
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->connection->prepare('SELECT * FROM products WHERE id = ? LIMIT 1');
        $stmt->bind_param('i', $id);
        $stmt->execute();

        $res = $stmt->get_result();
        $row = $res ? $res->fetch_assoc() : null;

        $stmt->close();
        return $row ?: null;
    }

    public function create(
        string $name,
        string $description,
        ?string $size,
        ?string $benefit,
        string $shopPage,
        string $shopSection,
        float $price,
        int $stock,
        ?float $oldPrice,
        ?int $discountPercent,
        ?string $imagePath
    ): int {
        $stmt = $this->connection->prepare(
            'INSERT INTO products
            (name, description, size, benefit, shop_page, shop_section, price, stock, old_price, discount_percent, image_path)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)'
        );

        $stmt->bind_param(
            'ssssssdidis',
            $name,
            $description,
            $size,
            $benefit,
            $shopPage,
            $shopSection,
            $price,
            $stock,
            $oldPrice,
            $discountPercent,
            $imagePath
        );

        $stmt->execute();

        $newId = (int)$this->connection->insert_id;

        $stmt->close();
        return $newId;
    }

    public function update(
        int $id,
        string $name,
        string $description,
        ?string $size,
        ?string $benefit,
        string $shopPage,
        string $shopSection,
        float $price,
        int $stock,
        ?float $oldPrice,
        ?int $discountPercent,
        ?string $imagePath
    ): void {
        $stmt = $this->connection->prepare(
            'UPDATE products SET
                name=?, description=?, size=?, benefit=?, shop_page=?, shop_section=?,
                price=?, stock=?, old_price=?, discount_percent=?, image_path=?
             WHERE id=?'
        );

        $stmt->bind_param(
            'ssssssdidisi',
            $name,
            $description,
            $size,
            $benefit,
            $shopPage,
            $shopSection,
            $price,
            $stock,
            $oldPrice,
            $discountPercent,
            $imagePath,
            $id
        );

        $stmt->execute();
        $stmt->close();
    }

    public function delete(int $id): void
    {
        $stmt = $this->connection->prepare('DELETE FROM products WHERE id=?');
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $stmt->close();
    }

    public function countAll(): int
    {
        $res = $this->connection->query('SELECT COUNT(*) AS c FROM products');
        if (!$res) return 0;
        $row = $res->fetch_assoc();
        return (int)($row['c'] ?? 0);
    }
}


