<?php
declare(strict_types=1);

session_start();

require_once __DIR__ . '/database.php';
require_once __DIR__ . '/SkintypePage.php';


$db = new Database();
$conn = $db->getConnection();


$page = new SkintypePage($conn);


$data = $page->latest();

if (!$data) {
   
    die('No data found in skintype_page table. Insert one row in phpMyAdmin.');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Skin Type</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500&family=Playfair+Display:wght@600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="skintype.css">
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

  

  <div class="icons">
    <a href="cart-page.php"><img src="icons/shopping cart.jpg" alt="Cart" /></a>
    <a href="logout.php"><img src="icons/user.jpg" alt="User" /></a>
  </div>
</header>

<section class="st-hero">
  
  <div class="st-pill"><?php echo htmlspecialchars((string)$data['hero_pill']); ?></div>

  
  <h1 class="st-heading"><?php echo htmlspecialchars((string)$data['hero_title']); ?></h1>

  <p class="st-subtext"><?php echo htmlspecialchars((string)$data['hero_subtext']); ?></p>
</section>

<section class="st-quiz">
  <div class="st-quiz-card">

   
    <form method="POST" action="skintype_result.php">

      
      <h2 class="st-quiz-question"><?php echo htmlspecialchars((string)$data['q1_text']); ?></h2>
      <div class="st-quiz-options">
        <label class="st-option">
          <input type="radio" name="q1" value="<?php echo htmlspecialchars((string)$data['q1_a_type']); ?>" required>
          <?php echo htmlspecialchars((string)$data['q1_a_text']); ?>
        </label>

        <label class="st-option">
          <input type="radio" name="q1" value="<?php echo htmlspecialchars((string)$data['q1_b_type']); ?>" required>
          <?php echo htmlspecialchars((string)$data['q1_b_text']); ?>
        </label>

        <label class="st-option">
          <input type="radio" name="q1" value="<?php echo htmlspecialchars((string)$data['q1_c_type']); ?>" required>
          <?php echo htmlspecialchars((string)$data['q1_c_text']); ?>
        </label>

        <label class="st-option">
          <input type="radio" name="q1" value="<?php echo htmlspecialchars((string)$data['q1_d_type']); ?>" required>
          <?php echo htmlspecialchars((string)$data['q1_d_text']); ?>
        </label>
      </div>

      <div style="height:18px;"></div>

   
      <h2 class="st-quiz-question"><?php echo htmlspecialchars((string)$data['q2_text']); ?></h2>
      <div class="st-quiz-options">
        <label class="st-option">
          <input type="radio" name="q2" value="<?php echo htmlspecialchars((string)$data['q2_a_type']); ?>" required>
          <?php echo htmlspecialchars((string)$data['q2_a_text']); ?>
        </label>

        <label class="st-option">
          <input type="radio" name="q2" value="<?php echo htmlspecialchars((string)$data['q2_b_type']); ?>" required>
          <?php echo htmlspecialchars((string)$data['q2_b_text']); ?>
        </label>

        <label class="st-option">
          <input type="radio" name="q2" value="<?php echo htmlspecialchars((string)$data['q2_c_type']); ?>" required>
          <?php echo htmlspecialchars((string)$data['q2_c_text']); ?>
        </label>

        <label class="st-option">
          <input type="radio" name="q2" value="<?php echo htmlspecialchars((string)$data['q2_d_type']); ?>" required>
          <?php echo htmlspecialchars((string)$data['q2_d_text']); ?>
        </label>
      </div>

      <div style="height:18px;"></div>

     
      <h2 class="st-quiz-question"><?php echo htmlspecialchars((string)$data['q3_text']); ?></h2>
      <div class="st-quiz-options">
        <label class="st-option">
          <input type="radio" name="q3" value="<?php echo htmlspecialchars((string)$data['q3_a_type']); ?>" required>
          <?php echo htmlspecialchars((string)$data['q3_a_text']); ?>
        </label>

        <label class="st-option">
          <input type="radio" name="q3" value="<?php echo htmlspecialchars((string)$data['q3_b_type']); ?>" required>
          <?php echo htmlspecialchars((string)$data['q3_b_text']); ?>
        </label>

        <label class="st-option">
          <input type="radio" name="q3" value="<?php echo htmlspecialchars((string)$data['q3_c_type']); ?>" required>
          <?php echo htmlspecialchars((string)$data['q3_c_text']); ?>
        </label>

        <label class="st-option">
          <input type="radio" name="q3" value="<?php echo htmlspecialchars((string)$data['q3_d_type']); ?>" required>
          <?php echo htmlspecialchars((string)$data['q3_d_text']); ?>
        </label>
      </div>

      <button type="submit" class="st-quiz-btn">Submit</button>
    </form>

  </div>
</section>



</body>
</html>


