<?php
declare(strict_types=1);

session_start();

require_once __DIR__ . '/database.php';
require_once __DIR__ . '/ShippingAddress.php';

$error = null;
$success = null;

$db = new Database();
$conn = $db->getConnection();

$shippingModel = new ShippingAddress($conn);




if (isset($_POST['create_shipping'])) {
    $firstName = trim($_POST['first_name'] ?? '');
    $lastName  = trim($_POST['last_name'] ?? '');
    $phone     = trim($_POST['phone'] ?? '');
    $street    = trim($_POST['street'] ?? '');
    $houseNo   = trim($_POST['house_no'] ?? '');
    $zip       = trim($_POST['zip'] ?? '');
    $state     = trim($_POST['state'] ?? '');
    $city      = trim($_POST['city'] ?? '');
    $note      = trim($_POST['note'] ?? '');

    
    $nameRegex  = "/^[A-Za-z\s]{2,}$/";
    $phoneRegex = "/^\+383\s?\d{2}\s?\d{3}\s?\d{3,4}$/";
    $zipRegex   = "/^\d{4,6}$/";

    if (!preg_match($nameRegex, $firstName)) {
        $error = "First name must be at least 2 letters!";
    } elseif (!preg_match($nameRegex, $lastName)) {
        $error = "Last name must be at least 2 letters!";
    } elseif (!preg_match($phoneRegex, $phone)) {
        $error = "Phone format: +383 44 123 456";
    } elseif ($street === '' || $houseNo === '' || $city === '') {
        $error = "Street, house number and city are required!";
    } elseif (!preg_match($zipRegex, $zip)) {
        $error = "ZIP must be 4–6 digits!";
    } else {
        try {
            $createdBy = $_SESSION['user'] ?? 'admin';

            $shippingModel->create(
                $firstName,
                $lastName,
                $phone,
                $street,
                $houseNo,
                $zip,
                $state !== '' ? $state : null,
                $city,
                $note !== '' ? $note : null,
                $createdBy
            );

            header("Location: admin-shipping.php?created=1");
            exit;
        } catch (Throwable $e) {
            $error = "Database error: " . $e->getMessage();
        }
    }
}


if (isset($_POST['delete_shipping'])) {
    $id = (int)($_POST['id'] ?? 0);

    if ($id <= 0) {
        $error = "Invalid id.";
    } else {
        try {
            $shippingModel->delete($id);
            header("Location: admin-shipping.php?deleted=1");
            exit;
        } catch (Throwable $e) {
            $error = "Delete failed: " . $e->getMessage();
        }
    }
}


if (isset($_GET['created'])) $success = "Shipping address created successfully!";
if (isset($_GET['deleted'])) $success = "Shipping address deleted successfully!";


$addresses = $shippingModel->all();

function e(string $v): string {
    return htmlspecialchars($v, ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Shipping Management</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500&family=Playfair+Display:wght@600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="admin.css">
</head>
<body>
<div class="admin-layout">

    <aside class="admin-sidebar">
        <h2 class="admin-logo">Leaf</h2>
        <ul class="admin-menu">
            <li><a href="admin.html">Dashboard</a></li>
            <li><a href="admin-homepage.html">Homepage</a></li>
            <li><a href="admin-products.html">Products</a></li>
            <li><a href="admin-about.html">About</a></li>
            <li><a href="admin-contact.php">Contact</a></li>
            <li class="active"><a href="admin-shipping.php">Shipping</a></li>
            <li><a href="admin-skintype.php">Skin Type</a></li>
            <li><a href="admin-users.html">Users</a></li>
             <li><a href="admin-orders.html">Orders</a></li>
             <li><a href="logout.php">Logout</a></li>
        </ul>
    </aside>

    <main class="admin-main">
        <h1>Shipping</h1>

        <?php if ($error): ?>
            <p style="color:red; font-weight:500;"><?= e($error) ?></p>
        <?php endif; ?>

        <?php if ($success): ?>
            <p style="color:green; font-weight:500;"><?= e($success) ?></p>
        <?php endif; ?>

       
        <div class="manage-card">
            <h3>Create Shipping Address</h3>

            <form method="post" action="admin-shipping.php">
                <input type="text" name="first_name" placeholder="First Name (Anna)" required>
                <input type="text" name="last_name" placeholder="Last Name (Smith)" required>
                <input type="text" name="phone" placeholder="Phone (+383 44 123 456)" required>

                <input type="text" name="street" placeholder="Street (Elm Street)" required>
                <input type="text" name="house_no" placeholder="House No (12)" required>

                <input type="text" name="zip" placeholder="ZIP (10000)" required>
                <input type="text" name="state" placeholder="State (optional)">
                <input type="text" name="city" placeholder="City (Pristina)" required>

                <input type="text" name="note" placeholder="Note (optional)">
                <button type="submit" name="create_shipping" class="btn-green">Create</button>
            </form>
        </div>

      
        <div class="manage-card">
            <h3>Existing Shipping Addresses</h3>

            <?php if (empty($addresses)): ?>
                <p>No shipping addresses found.</p>
            <?php else: ?>
                <?php foreach ($addresses as $a): ?>
                    <div class="product-item">
                        <p><strong>ID:</strong> <?= (int)$a['id'] ?></p>
                        <p><strong>Name:</strong> <?= e((string)$a['first_name']) ?> <?= e((string)$a['last_name']) ?></p>
                        <p><strong>Phone:</strong> <?= e((string)$a['phone']) ?></p>
                        <p><strong>Address:</strong>
                            <?= e((string)$a['street']) ?> <?= e((string)$a['house_no']) ?>,
                            <?= e((string)$a['city']) ?>
                            <?php if (!empty($a['state'])): ?>, <?= e((string)$a['state']) ?><?php endif; ?>
                            <?= " - " . e((string)$a['zip']) ?>
                        </p>

                        <?php if (!empty($a['note'])): ?>
                            <p><strong>Note:</strong> <?= e((string)$a['note']) ?></p>
                        <?php endif; ?>

                        <p><strong>Created By:</strong> <?= e((string)$a['created_by']) ?></p>

                       
                        <form method="post" action="admin-shipping.php" onsubmit="return confirm('Delete this shipping address?');">
                            <input type="hidden" name="id" value="<?= (int)$a['id'] ?>">
                            <button type="submit" name="delete_shipping" class="btn-green" style="background:#c0392b;">
                                Delete
                            </button>
                        </form>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

    </main>

</div>
</body>
</html>

