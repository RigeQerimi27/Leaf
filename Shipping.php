<?php
declare(strict_types=1);

session_start();

require_once __DIR__ . '/database.php';
require_once __DIR__ . '/ShippingAddress.php';

$error = null;


$returnTo = $_GET['return_to'] ?? 'cart-page.php';


if (isset($_POST['save'])) {
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
            $db = new Database();
            $conn = $db->getConnection();

            $shipping = new ShippingAddress($conn);

           
            $createdBy = $_SESSION['user'] ?? 'guest';

            $newId = $shipping->create(
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

            
            $_SESSION['shipping_address_id'] = $newId;

            header('Location: ' . $returnTo);
            exit;
        } catch (Throwable $e) {
            $error = "Database error: " . $e->getMessage();
        }
    }
}


function e(string $v): string {
    return htmlspecialchars($v, ENT_QUOTES, 'UTF-8');
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Shipping Leaf</title>
  <link rel="stylesheet" href="shipping.css">
</head>

<body>
<header class="navbar">
  <div class="logo">Leaf</div>
  <nav>
    <ul class="nav-links">
      <li><a href="homepage.php">Home</a></li>
      <li><a href="shop-bestsellers.php">Shop</a></li>
      <li><a href="AboutUs.php">About</a></li>
      <li><a href="contact.php">Contact</a></li>
    </ul>
  </nav>
</header>

<section class="shipping-section">
  <div class="wrap">
    <h2 class="shipping-title" style="margin-top:40px;">Shipping Address</h2>
    <p class="shipping-subtitle">Fill in the address where you'd like to receive your order.</p>

    <div class="address-card">
      <form class="address-form"
            method="post"
            action="shipping.php?return_to=<?php echo urlencode($returnTo); ?>"
            autocomplete="on"
            novalidate>

        <div>
          <label for="firstName">First Name</label>
          <input id="firstName" name="first_name" type="text" required placeholder="Anna"
                 value="<?php echo e($firstName ?? ''); ?>">
        </div>

        <div>
          <label for="lastName">Last Name</label>
          <input id="lastName" name="last_name" type="text" required placeholder="Smith"
                 value="<?php echo e($lastName ?? ''); ?>">
        </div>

        <div>
          <label for="phone">Phone Number</label>
          <input id="phone" name="phone" type="tel" required placeholder="+383 44 123 456"
                 value="<?php echo e($phone ?? ''); ?>">
        </div>

        <div>
          <label for="street">Street Name</label>
          <input id="street" name="street" type="text" required placeholder="Elm Street"
                 value="<?php echo e($street ?? ''); ?>">
        </div>

        <div>
          <label for="house">House No.</label>
          <input id="house" name="house_no" type="text" required placeholder="12"
                 value="<?php echo e($houseNo ?? ''); ?>">
        </div>

        <div>
          <label for="zip">Post / ZIP Code</label>
          <input id="zip" name="zip" type="text" required placeholder="10000"
                 value="<?php echo e($zip ?? ''); ?>">
        </div>

        <div>
          <label for="state">State</label>
          <input id="state" name="state" type="text" placeholder="Region / State"
                 value="<?php echo e($state ?? ''); ?>">
        </div>

        <div>
          <label for="city">City</label>
          <input id="city" name="city" type="text" required placeholder="Pristina"
                 value="<?php echo e($city ?? ''); ?>">
        </div>

        <div class="full">
          <label for="note">Delivery Note (optional)</label>
          <input id="note" name="note" type="text" placeholder="Doorbell code, leave at reception, etc."
                 value="<?php echo e($note ?? ''); ?>">
        </div>

        <p class="full" style="margin:10px 0;">
          <?php if ($error) echo "<span style='color:red;'>" . e($error) . "</span>"; ?>
        </p>

        <div class="full save-row">
          <button type="submit" name="save" class="btn-save">Save Address</button>
        </div>
      </form>
    </div>
  </div>
</section>

</body>
</html>









