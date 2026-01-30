<?php
declare(strict_types=1);

require_once __DIR__ . '/database.php';
require_once __DIR__ . '/Product.php';



if (!class_exists('Product')) {
    die('Product class NOT loaded');
}


$db = new Database();
$conn = $db->getConnection();

$productModel = new Product($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Leaf - Shop</title>
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
      <li><a href="Contact.php">Contact</a></li>
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

<div class="shop-tabs">
  <a href="shop-bestsellers.php" class="<?php echo $page === 'bestsellers' ? 'tab-active' : 'tab-shop'; ?>">Best sellers</a>
  <a href="shop-skincare.php" class="<?php echo $page === 'skincare' ? 'tab-active' : 'tab-shop'; ?>">Skincare</a>
  <a href="shop-bodycare.php" class="<?php echo $page === 'bodycare' ? 'tab-active' : 'tab-shop'; ?>">Body care</a>
  <a href="shop-bath-relax.php" class="<?php echo $page === 'bath-relax' ? 'tab-active' : 'tab-shop'; ?>">Bath &amp; Relax</a>
</div>

<?php foreach ($sections as $sectionKey => $sectionTitle): ?>
  <?php $products = $productModel->allByPageAndSection($page, $sectionKey); ?>

  <section class="shop-section">
    <h2 class="section-title"><?php echo htmlspecialchars($sectionTitle); ?></h2>

    <div class="product-grid">
      <?php foreach ($products as $p): ?>
        <?php $isSale = !empty($p['discount_percent']) && !empty($p['old_price']); ?>

        <div class="product-card <?php echo $isSale ? 'sale' : ''; ?>">
          <?php if ($isSale): ?>
            <span class="badge-sale">-<?php echo (int)$p['discount_percent']; ?>%</span>
          <?php endif; ?>

          <img
            src="<?php echo htmlspecialchars($p['image_path'] ?: 'images/placeholder.jpg'); ?>"
            alt="<?php echo htmlspecialchars($p['name']); ?>"
          >

          <h3><?php echo htmlspecialchars($p['name']); ?></h3>

          <p class="product-meta">
            <?php
              $metaParts = [];
              if (!empty($p['size'])) { $metaParts[] = $p['size']; }
              if (!empty($p['benefit'])) { $metaParts[] = $p['benefit']; }
              echo htmlspecialchars(implode(' · ', $metaParts));
            ?>
          </p>

          <?php if ($isSale): ?>
            <p class="price">
              <span class="new-price">€<?php echo number_format((float)$p['price'], 2); ?></span>
              <span class="old-price">€<?php echo number_format((float)$p['old_price'], 2); ?></span>
              <span class="save">Save <?php echo (int)$p['discount_percent']; ?>%</span>
            </p>
          <?php else: ?>
            <div class="price-row">
              <p class="new-price">€<?php echo number_format((float)$p['price'], 2); ?></p>
              <button class="product-btn" type="button">Add to Cart</button>
            </div>
          <?php endif; ?>
        </div>
      <?php endforeach; ?>
    </div>
  </section>
<?php endforeach; ?>

<footer class="footer">
  <div class="footer-left">
    <div class="footer-logo">Leaf</div>
    <p class="footer-text">Natural beauty essentials for radiant, healthy skin.</p>
  </div>

  <div class="footer-links">
    <div>
      <h4>Shop</h4>
      <ul>
        <li><a href="shop-bestsellers.php">All Products</a></li>
        <li><a href="shop-bestsellers.php">Bestsellers</a></li>
        <li><a href="#">New arrivals</a></li>
        <li><a href="#">Gift sets</a></li>
      </ul>
    </div>

    <div>
      <h4>Support</h4>
      <ul>
        <li><a href="Contact.php">Contact us</a></li>
        <li><a href="AboutUs.php">About us</a></li>
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

</body>
</html>
