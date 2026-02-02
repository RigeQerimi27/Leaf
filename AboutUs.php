<?php
declare(strict_types=1);

session_start();

require_once __DIR__ . '/database.php';
require_once __DIR__ . '/AboutContent.php';


$db = new Database();
$conn = $db->getConnection();


$about = new AboutContent($conn);
$data  = $about->latest();


$aboutSubtitle = $data['about_subtitle'] ?? 'Our story of natural beauty and sustainable skincare';
$missionText   = $data['mission_text'] ?? "At Leaf, we believe that true beauty comes from nature...\n\n...";
$whySubtitle   = $data['why_subtitle'] ?? 'Our commitment to clean, effective beauty shines through in every product';

$feature1Title = $data['feature1_title'] ?? 'Natural Ingredients';
$feature1Text  = $data['feature1_text']  ?? 'Only the finest botanicals...';

$feature2Title = $data['feature2_title'] ?? 'Visible Results';
$feature2Text  = $data['feature2_text']  ?? 'Clinically proven formulas...';

$feature3Title = $data['feature3_title'] ?? 'Cruelty Free';
$feature3Text  = $data['feature3_text']  ?? 'Never tested on animals...';

$feature4Title = $data['feature4_title'] ?? 'Dermatologist Tested';
$feature4Text  = $data['feature4_text']  ?? 'Gentle yet effective...';
?>


<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>AboutUs</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500&family=Playfair+Display:wght@600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="aboutus.css">
  </head>
  <body>
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

      

      <div class="icons">
  <a href="cart-page.php"><img src="icons/shopping cart.jpg" alt="Cart"></a>

  <div class="user-menu">
    <img src="icons/user.jpg" alt="User" class="user-icon">

    <div class="logout-dropdown">
      <a href="logout.php">Logout</a>
    </div>
  </div>
</div> 
    </header>

    <section class="about">
      <h2>About Leaf</h2>
      <p><?php echo htmlspecialchars($aboutSubtitle); ?></p>
    </section>

    <section class="mission">
      <h2>Our Mission</h2>

      
      <p><?php echo nl2br(htmlspecialchars($missionText)); ?></p>
    </section>

    <section class="whychoose">
      <h2>Why choose Leaf</h2>
      <p><?php echo htmlspecialchars($whySubtitle); ?></p>
    </section>

    <div class="features">
      <div class="feature">
        <img src="icons/Naturale.png" alt="Natural Ingredients">
        <h3><?php echo htmlspecialchars($feature1Title); ?></h3>
        <p><?php echo htmlspecialchars($feature1Text); ?></p>
      </div>

      <div class="feature">
        <img src="icons/Rez.png" alt="Visible Results">
        <h3><?php echo htmlspecialchars($feature2Title); ?></h3>
        <p><?php echo htmlspecialchars($feature2Text); ?></p>
      </div>

      <div class="feature">
        <img src="icons/Free.png" alt="Cruelty Free">
        <h3><?php echo htmlspecialchars($feature3Title); ?></h3>
        <p><?php echo htmlspecialchars($feature3Text); ?></p>
      </div>

      <div class="feature">
        <img src="icons/DT.png" alt="Dermatologist Tested">
        <h3><?php echo htmlspecialchars($feature4Title); ?></h3>
        <p><?php echo htmlspecialchars($feature4Text); ?></p>
      </div>
    </div>

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
          <li><a href="skintype.php">Skintype</a></li>
          <li><a href="shop-skincare.php">Skincare</a></li>
        </ul>
      </div>

      <div>
        <h4>Support</h4>
        <ul>
          <li><a href="Contact.php">Contact us</a></li>
          <li><a href="AboutUs.php">About us</a></li>
          <li><a href="Shipping.php">Shipping info</a></li>
          <li><a href="shop-bodycare.php">Bodycare</a></li>
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
