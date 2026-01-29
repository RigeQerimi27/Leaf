<?php

class Product {
    private $conn;
    private $table = "products";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create($data) {
        $sql = "INSERT INTO {$this->table}
        (name, description, size, benefit, shop_page, shop_section, price, old_price, discount_percent, stock, image)
        VALUES
        (:name, :description, :size, :benefit, :shop_page, :shop_section, :price, :old_price, :discount, :stock, :image)";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            ':name' => $data['name'],
            ':description' => $data['description'],
            ':size' => $data['size'],
            ':benefit' => $data['benefit'],
            ':shop_page' => $data['shop_page'],
            ':shop_section' => $data['shop_section'],
            ':price' => $data['price'],
            ':old_price' => $data['old_price'],
            ':discount' => $data['discount_percent'],
            ':stock' => $data['stock'],
            ':image' => $data['image']
        ]);
    }

    public function getAll() {
        $stmt = $this->conn->query("SELECT * FROM {$this->table} ORDER BY created_at DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
