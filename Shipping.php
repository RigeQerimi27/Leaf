<?php
declare(strict_types=1);

session_start();

require_once __DIR__ . '/database.php';
require_once __DIR__ . '/ShippingAddress.php';

$error = null;
$success = null;

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
    $phoneRegex = "/^\+383\s?\d{2}\s?\d{3}\s?\d{3,4}$/"; // +383 44 123 456
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
        $db = new Database();
        $conn = $db->getConnection();

        $shipping = new ShippingAddress($conn);
        $createdBy = $_SESSION['user'] ?? 'guest';

        $shipping->create(
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

        $success = "Address saved successfully!";
    }
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

  <div class="search-bar">
    <input type="text" placeholder="Search products...." class="search-input">
  </div>

  <div class="icons">
    <a href="#"><img src="icons/shopping cart.jpg"></a>
    <a href="#"><img src="icons/user.jpg"></a>
  </div>
</header>

<section class="shipping-section">
  <div class="wrap">

  

    <h2 class="shipping-title" style="margin-top:40px;">Shipping Address</h2>
    <p class="shipping-subtitle">Fill in the address where you'd like to receive your order.</p>

    <div class="address-card">

      <form id="shippingForm"
            class="address-form"
            method="post"
            action="shipping.php"
            autocomplete="on"
            novalidate>

        <div>
          <label for="firstName">First Name</label>
          <input id="firstName" name="first_name" type="text" required placeholder="Anna">
        </div>

        <div>
          <label for="lastName">Last Name</label>
          <input id="lastName" name="last_name" type="text" required placeholder="Smith">
        </div>

        <div>
          <label for="phone">Phone Number</label>
          <input id="phone" name="phone" type="tel" required placeholder="+383 44 123 456">
        </div>

        <div>
          <label for="street">Street Name</label>
          <input id="street" name="street" type="text" required placeholder="Elm Street">
        </div>

        <div>
          <label for="house">House No.</label>
          <input id="house" name="house_no" type="text" required placeholder="12">
        </div>

        <div>
          <label for="zip">Post / ZIP Code</label>
          <input id="zip" name="zip" type="text" required placeholder="10000">
        </div>

        <div>
          <label for="state">State</label>
          <input id="state" name="state" type="text" placeholder="Region / State">
        </div>

        <div>
          <label for="city">City</label>
          <input id="city" name="city" type="text" required placeholder="Pristina">
        </div>

        <div class="full">
          <label for="note">Delivery Note (optional)</label>
          <input id="note" name="note" type="text" placeholder="Doorbell code, leave at reception, etc.">
        </div>

        <p class="full" id="msg" style="margin:10px 0;">
          <?php
            if ($error) echo "<span style='color:red;'>$error</span>";
            if ($success) echo "<span style='color:green;'>$success</span>";
          ?>
        </p>

        <div class="full save-row">
          <button type="submit" name="save" id="saveBtn" class="btn-save">Save Address</button>
        </div>

      </form>

      <div class="helper saved-info">
        Saved addresses are stored locally in your browser (only visible on this device).
      </div>

    </div>

  </div>
</section>

 <footer class="footer">
    <div class="footer-left">
      <div class="footer-logo">Leaf</div>
      <p class="footer-text">Natural beauty essentials for radiant, healthy skin.</p>
    </div>

    <div class="footer-links">
      <div>
        <h4>Shop</h4>
        <ul>
          <li><a href="shop-bestsellers.html">All Products</a></li>
          <li><a href="shop-bestsellers.html">Bestsellers</a></li>
          <li><a href="#">New arrivals</a></li>
          <li><a href="#">Gift sets</a></li>
        </ul>
      </div>

      <div>
        <h4>Support</h4>
        <ul>
          <li><a href="Contact.php">Contact us</a></li>
          <li><a href="AboutUs.html">About us</a></li>
          <li><a href="Shipping.html">Shipping info</a></li>
          <li><a href="#">Returns</a></li>
        </ul>
      </div>
    </div>

    <div class="footer-social">
      <h4>Follow us</h4>
      <div class="social-icons">
        <a href="#"><img src="icons/instagram.jpg" alt="Instagram"></a>
        <a href="#"><img src="icons/facebook.jpg" alt="Facebook"></a>
        <a href="#"><img src="icons/tiktok.jpg" alt="TikTok"></a>
      </div>
    </div>
  </footer>

  <p class="copyright">
    © 2025 Leaf Skincare. All rights reserved.
  </p>

<script src="shipping.js"></script>
</body>
</html>






