<?php
declare(strict_types=1);


class CartSession
{
    private const KEY = 'cart';

    public function __construct()
    {
        if (!isset($_SESSION[self::KEY]) || !is_array($_SESSION[self::KEY])) {
            $_SESSION[self::KEY] = [];
        }
    }

    public function inc(int $productId): void
    {
        if ($productId <= 0) return;
        $_SESSION[self::KEY][$productId] = $this->qty($productId) + 1;
    }

    public function dec(int $productId): void
    {
        if ($productId <= 0) return;

        $newQty = $this->qty($productId) - 1;
        if ($newQty <= 0) {
            $this->remove($productId);
        } else {
            $_SESSION[self::KEY][$productId] = $newQty;
        }
    }

    public function remove(int $productId): void
    {
        unset($_SESSION[self::KEY][$productId]);
    }

    public function clear(): void
    {
        $_SESSION[self::KEY] = [];
    }

    public function items(): array
    {
        return $_SESSION[self::KEY];
    }

    public function qty(int $productId): int
    {
        return (int)($_SESSION[self::KEY][$productId] ?? 0);
    }

    public function isEmpty(): bool
    {
        return empty($_SESSION[self::KEY]);
    }
}
