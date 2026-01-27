<?php
declare(strict_types=1);

session_start(); 

require_once __DIR__ . '/database.php'; 


$db = new Database();


$conn = $db->getConnection();


$result = $conn->query("SELECT * FROM homepage_content ORDER BY id DESC LIMIT 1");


$data = $result ? $result->fetch_assoc() : null;


$heroTitle = $data['hero_title'] ?? "Natural Beauty,<br><span class='highlight'>Pure Radiance</span>";
$heroSub   = $data['hero_sub'] ?? "Explore our fresh, organic skincare collection crafted from nature’s finest ingredients for healthy, radiant skin.";

$skinTitle   = $data['skin_title'] ?? "Not sure about your skin type?";
$skinText    = $data['skin_text'] ?? "Understanding your skin type helps you choose the right products for a healthy, glowing complexion.";
$skinBtnText = $data['skin_btn_text'] ?? "Find your skin type";
$skinBtnLink = $data['skin_btn_link'] ?? "skintype.html";

$skinInfoTitle = $data['skin_info_title'] ?? "Why understanding your skin type matters";
$skinInfoText  = $data['skin_info_text'] ?? "Choosing the right skincare routine starts with knowing your skin.";

$topPicksTitle = $data['top_picks_title'] ?? "Top picks";
$topPicksSub   = $data['top_picks_sub'] ?? "Explore our top picks, hand-selected for their purity, performance, and proven results.";
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Leaf - Natural Beauty</title>

  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500&family=Playfair+Display:wght@600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="homepage.css">
</head>

<body>

  <header class="navbar">
    <div class="logo">Leaf</div>

    <nav>
      <ul class="nav-links">
       
        <li><a href="homepage.php">Home</a></li>
        <li><a href="shop-bestsellers.html">Shop</a></li>
        <li><a href="AboutUs.html">About</a></li>
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

  <section class="hero">
    <div class="hero-text">

    
      <h1 class="hero-title">
        <?php
         
          echo $heroTitle;
        ?>
      </h1>

      
      <p class="hero-sub"><?php echo htmlspecialchars($heroSub); ?></p>

      <div class="hero-buttons">
        <a href="shop-bestsellers.html" class="btn-green">Shop collection</a>
        <a href="AboutUs.html" class="btn-outline">Learn more</a>
      </div>
    </div>

    <div class="hero-img">
      <button type="button" class="hero-arrow left" onclick="changeHero(-1)">‹</button>

      
      <img src="images/aloevera.jpg" id="heroImage" alt="Beauty products">

      <button type="button" class="hero-arrow right" onclick="changeHero(1)">›</button>
    </div>
  </section>

  <section class="skin-type-section-wrapper">

    <div class="homepage-blur-right"></div>
    <div class="homepage-blur-left"></div>

    <div class="skin-type-container">
      <div class="skin-type-section">

        
        <h3><?php echo htmlspecialchars($skinTitle); ?></h3>
        <p><?php echo htmlspecialchars($skinText); ?></p>

        
        <a href="<?php echo htmlspecialchars($skinBtnLink); ?>" class="skin-type-btn">
          <?php echo htmlspecialchars($skinBtnText); ?>
        </a>
      </div>

      <div class="skin-type-info">
       
        <h4><?php echo htmlspecialchars($skinInfoTitle); ?></h4>
        <p><?php echo htmlspecialchars($skinInfoText); ?></p>
      </div>
    </div>
  </section>

  <section class="top-picks">
   
    <h2><?php echo htmlspecialchars($topPicksTitle); ?></h2>
    <p class="section-text"><?php echo htmlspecialchars($topPicksSub); ?></p>

    
    <div class="product-list">

      <div class="product">
        <img src="images/radiance serum.jpg" alt="Radiance Serum" class="product-image">
        <h3 class="product-name">Radiance Serum</h3>
        <p class="product-desc">Brightening vitamin C serum</p>
        <p class="product-price">€25.00</p>
        <button class="product-button">Add to Cart</button>
      </div>

      <div class="product">
        <img src="images/Nourishing cream.jpg" alt="Nourishing Cream" class="product-image">
        <h3 class="product-name">Nourishing Cream</h3>
        <p class="product-desc">Deep hydration for all skin types</p>
        <p class="product-price">€32.99</p>
        <button class="product-button">Add to Cart</button>
      </div>

      <div class="product">
        <img src="images/gentle cleanser.jpg" alt="Gentle Cleanser" class="product-image">
        <h3 class="product-name">Gentle Cleanser</h3>
        <p class="product-desc">Purifying botanical face wash</p>
        <p class="product-price">€23.99</p>
        <button class="product-button">Add to Cart</button>
      </div>

    </div>
  </section>

  <section class="info-cards">
    

    <div class="info-card">
      <h3 class="card-title">Shop now</h3>
      <p class="card-text">Discover our full collection of luxury skincare.</p>
      <a href="#" class="btn-light">Explore</a>
    </div>

    <div class="info-card">
      <h3 class="card-title">Our Story</h3>
      <p class="card-text">Learn about our commitment to natural beauty.</p>
      <a href="#" class="btn-light">Read more</a>
    </div>

    <div class="info-card">
      <h3 class="card-title">Get in touch</h3>
      <p class="card-text">Questions? We’re here to help you.</p>
      <a href="#" class="btn-light">Contact</a>
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

  <script src="main.js"></script>
</body>
</html>
