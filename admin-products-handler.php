<?php
declare(strict_types=1);

session_start(); 

require_once __DIR__ . '/database.php';
require_once __DIR__ . '/Product.php';


$db = new Database();
$conn = $db->getConnection();
$productModel = new Product($conn);


function flashAndRedirect(string $msg, string $to = 'admin-products.php'): void {
    $_SESSION['flash'] = $msg;
    header('Location: ' . $to);
    exit;
}


function nullIfEmpty(?string $v): ?string {
    if ($v === null) return null;
    $v = trim($v);
    return $v === '' ? null : $v;
}


function nullFloat(?string $v): ?float {
    $v = nullIfEmpty($v);
    return $v === null ? null : (float)$v;
}


function nullInt(?string $v): ?int {
    $v = nullIfEmpty($v);
    return $v === null ? null : (int)$v;
}

try {
    
    $action = $_POST['action'] ?? '';

    
    if ($action === 'create') {
        $name = trim((string)($_POST['name'] ?? ''));
        $description = trim((string)($_POST['description'] ?? ''));
        $shopPage = trim((string)($_POST['shop_page'] ?? ''));
        $shopSection = trim((string)($_POST['shop_section'] ?? ''));

        
        if ($name === '' || $description === '' || $shopPage === '' || $shopSection === '') {
            flashAndRedirect('Fill required fields (*).');
        }

        $productModel->create(
            $name,
            $description,
            nullIfEmpty($_POST['size'] ?? null),
            nullIfEmpty($_POST['benefit'] ?? null),
            $shopPage,
            $shopSection,
            (float)($_POST['price'] ?? 0),
            (int)($_POST['stock'] ?? 0),
            nullFloat($_POST['old_price'] ?? null),
            nullInt($_POST['discount_percent'] ?? null),
            nullIfEmpty($_POST['image_path'] ?? null)
        );

        flashAndRedirect('Product created.');
    }

   
    if ($action === 'update') {
        $id = (int)($_POST['id'] ?? 0);
        if ($id <= 0) flashAndRedirect('Invalid ID.');

        $name = trim((string)($_POST['name'] ?? ''));
        $description = trim((string)($_POST['description'] ?? ''));
        $shopPage = trim((string)($_POST['shop_page'] ?? ''));
        $shopSection = trim((string)($_POST['shop_section'] ?? ''));

        if ($name === '' || $description === '' || $shopPage === '' || $shopSection === '') {
            flashAndRedirect('Fill required fields (*).', 'admin-products.php?edit=' . $id);
        }

        $productModel->update(
            $id,
            $name,
            $description,
            nullIfEmpty($_POST['size'] ?? null),
            nullIfEmpty($_POST['benefit'] ?? null),
            $shopPage,
            $shopSection,
            (float)($_POST['price'] ?? 0),
            (int)($_POST['stock'] ?? 0),
            nullFloat($_POST['old_price'] ?? null),
            nullInt($_POST['discount_percent'] ?? null),
            nullIfEmpty($_POST['image_path'] ?? null)
        );

        flashAndRedirect('Product updated.');
    }

    
    if ($action === 'delete') {
        $id = (int)($_POST['id'] ?? 0);
        if ($id <= 0) flashAndRedirect('Invalid ID.');

        $productModel->delete($id);
        flashAndRedirect('Product deleted.');
    }

   
    flashAndRedirect('Unknown action.');
} catch (Throwable $e) {
   
    flashAndRedirect('Error: ' . $e->getMessage());
}
