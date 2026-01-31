<?php
declare(strict_types=1);

session_start();

require_once __DIR__ . '/database.php';
require_once __DIR__ . '/CartSession.php';
require_once __DIR__ . '/Order.php';

$cartObj = new CartSession();
$cart = $cartObj->items();

$shippingId = (int)($_SESSION['shipping_address_id'] ?? 0);

if (empty($cart)) { header('Location: cart-page.php'); exit; }
if ($shippingId <= 0) { header('Location: shipping.php?return_to=cart-page.php'); exit; }

$db = new Database();
$conn = $db->getConnection();

$createdBy = $_SESSION['user'] ?? 'guest';


$ids = array_keys($cart);
$placeholders = implode(',', array_fill(0, count($ids), '?'));
$types = str_repeat('i', count($ids));

$stmt = $conn->prepare("SELECT id, price FROM products WHERE id IN ($placeholders)");
$stmt->bind_param($types, ...$ids);
$stmt->execute();
$res = $stmt->get_result();
$rows = $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
$stmt->close();

$priceMap = [];
foreach ($rows as $r) {
    $priceMap[(int)$r['id']] = (float)$r['price'];
}


$subtotal = 0.0;
foreach ($cart as $pid => $qty) {
    $pid = (int)$pid;
    $qty = (int)$qty;
    if ($qty <= 0 || !isset($priceMap[$pid])) continue;
    $subtotal += $priceMap[$pid] * $qty;
}


$orderModel = new Order($conn);
$orderId = $orderModel->create($createdBy, $shippingId, $subtotal);


foreach ($cart as $pid => $qty) {
    $pid = (int)$pid;
    $qty = (int)$qty;
    if ($qty <= 0 || !isset($priceMap[$pid])) continue;

    $orderModel->addItem($orderId, $pid, $qty, $priceMap[$pid]);
}


$cartObj->clear();

header('Location: cart-page.php');
exit;
