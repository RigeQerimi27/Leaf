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
        $statement = $this->connection->prepare(
            'SELECT * FROM products WHERE shop_page = ? ORDER BY created_at DESC'
        );
        $statement->bind_param('s', $shopPage);
        $statement->execute();

        $result = $statement->get_result();
        $rows = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];

        $statement->close();
        return $rows;
    }

    // Merr produktet për një shop page + një section (p.sh. on-sale, cleansers, bath-soaks...)
    public function allByPageAndSection(string $shopPage, string $shopSection): array
    {
        $statement = $this->connection->prepare(
            'SELECT * FROM products WHERE shop_page = ? AND shop_section = ? ORDER BY created_at DESC'
        );
        $statement->bind_param('ss', $shopPage, $shopSection);
        $statement->execute();

        $result = $statement->get_result();
        $rows = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];

        $statement->close();
        return $rows;
    }

    // Merr të gjitha produktet (për admin list)
    public function all(): array
    {
        $result = $this->connection->query('SELECT * FROM products ORDER BY created_at DESC');
        if (!$result) return [];
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Krijo produkt (për admin create)
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
    ): void {
        $statement = $this->connection->prepare(
            'INSERT INTO products
            (name, description, size, benefit, shop_page, shop_section, price, stock, old_price, discount_percent, image_path)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)'
        );

        // s=string, d=double, i=int
        $statement->bind_param(
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

        $statement->execute();
        $statement->close();
    }
}
