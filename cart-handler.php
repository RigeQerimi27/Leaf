<?php
declare(strict_types=1);

session_start();

require_once __DIR__ . '/CartSession.php';

$cart = new CartSession();

$action = $_POST['action'] ?? '';
$productId = (int)($_POST['product_id'] ?? 0);

switch ($action) {
    case 'add':
    case 'inc':
        $cart->inc($productId);
        break;

    case 'dec':
        $cart->dec($productId);
        break;

    case 'remove':
        $cart->remove($productId);
        break;

    case 'clear':
        $cart->clear();
        break;
}

header('Location: cart-page.php');
exit;
