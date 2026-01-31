<?php
declare(strict_types=1);

session_start();

require_once __DIR__ . '/database.php';
require_once __DIR__ . '/CartSession.php';

$cartObj = new CartSession();
$cart = $cartObj->items(); 

$productRows = [];
$subtotal = 0.0;

if (!empty($cart)) {
    $db = new Database();
    $conn = $db->getConnection();

    
    $ids = array_keys($cart);

    
    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    $types = str_repeat('i', count($ids));

    $stmt = $conn->prepare(
        "SELECT id, name, price, image_path
         FROM products
         WHERE id IN ($placeholders)"
    );

    $stmt->bind_param($types, ...$ids);
    $stmt->execute();

    $res = $stmt->get_result();
    $productRows = $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
    $stmt->close();

    
    foreach ($productRows as $p) {
        $pid = (int)$p['id'];
        $qty = (int)($cart[$pid] ?? 0);
        $subtotal += (float)$p['price'] * $qty;
    }
}

$hasShipping = !empty($_SESSION['shipping_address_id']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Leaf - Cart</title>

  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500&family=Playfair+Display:wght@600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="shop.css">
</head>

<body class="shop-body">

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

  <div class="icons">
  
  <a href="cart-page.php">
    <img src="icons/shopping cart.jpg" alt="Cart">
  </a>

 
  <a href="#">
    <img src="icons/user.jpg" alt="User">
  </a>
</div>

</header>

<section class="shop-section">
  <h2 class="section-title">Your Cart</h2>

  <div class="product-grid">
    <?php if (empty($cart)): ?>
      <p class="product-meta">Cart is empty.</p>
    <?php else: ?>

      <?php foreach ($productRows as $p): ?>
        <?php
          $pid = (int)$p['id'];
          $qty = (int)($cart[$pid] ?? 0);
          $img = $p['image_path'] ?: 'images/placeholder.jpg';
        ?>

        <div class="product-card">
          <img src="<?php echo htmlspecialchars($img); ?>" alt="<?php echo htmlspecialchars($p['name']); ?>">
          <h3><?php echo htmlspecialchars($p['name']); ?></h3>

         
          <p class="product-meta">Qty: <?php echo $qty; ?></p>

          <div class="price-row">
            <p class="new-price">€<?php echo number_format((float)$p['price'], 2); ?></p>

           
            <div style="display:flex; gap:10px;">

              
              <form method="post" action="cart-handler.php">
                <input type="hidden" name="action" value="dec">
                <input type="hidden" name="product_id" value="<?php echo $pid; ?>">
                <button class="product-btn" type="submit">-</button>
              </form>

             
              <form method="post" action="cart-handler.php">
                <input type="hidden" name="action" value="inc">
                <input type="hidden" name="product_id" value="<?php echo $pid; ?>">
                <button class="product-btn" type="submit">+</button>
              </form>

            </div>
          </div>

          
          <div style="margin-top:12px;">
            <form method="post" action="cart-handler.php">
              <input type="hidden" name="action" value="remove">
              <input type="hidden" name="product_id" value="<?php echo $pid; ?>">
              <button class="product-btn" type="submit">Remove</button>
            </form>
          </div>
        </div>

      <?php endforeach; ?>

    <?php endif; ?>
  </div>
</section>

<section class="shop-section">
  <h2 class="section-title">Summary</h2>

  <div class="product-card">
    <p class="product-meta" style="margin-bottom:10px;">Subtotal</p>
    <p class="new-price" style="font-size:22px; font-weight:600;">
      €<?php echo number_format($subtotal, 2); ?>
    </p>

    <div style="margin-top:18px;">
      <?php if (!empty($cart) && !$hasShipping): ?>
     
        <a class="product-btn"
           href="shipping.php?return_to=cart-page.php"
           style="width:100%; display:block; text-align:center;">
          Add shipping info
        </a>

      <?php elseif (!empty($cart) && $hasShipping): ?>
      
        <form method="post" action="place-order.php">
          <button class="product-btn" type="submit" style="width:100%;">Order Now</button>
        </form>

      <?php else: ?>
       
        <button class="product-btn" type="button" style="width:100%;" disabled>Order Now</button>
      <?php endif; ?>
    </div>

    <div style="margin-top:10px;">
      <a href="shop-bestsellers.php" style="text-decoration:none; color:inherit;">
        ← Continue shopping
      </a>
    </div>
  </div>
</section>

</body>
</html>
