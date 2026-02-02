<?php
declare(strict_types=1);


session_start();

require_once __DIR__ . '/database.php';
require_once __DIR__ . '/AboutContent.php';

$db = new Database();
$conn = $db->getConnection();

$about = new AboutContent($conn);

$message = null;




if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $section = (string)($_POST['section'] ?? '');

  
    if ($section === 'header') {
        $ok = $about->save([
            'about_subtitle' => trim((string)($_POST['about_subtitle'] ?? '')),
        ]);
        $message = $ok ? 'About header saved.' : 'Error saving about header.';
    }

   
    if ($section === 'mission') {
        $ok = $about->save([
            'mission_text' => trim((string)($_POST['mission_text'] ?? '')),
        ]);
        $message = $ok ? 'Mission saved.' : 'Error saving mission.';
    }

    
    if ($section === 'why') {
        $ok = $about->save([
            'why_subtitle' => trim((string)($_POST['why_subtitle'] ?? '')),
        ]);
        $message = $ok ? 'Why subtitle saved.' : 'Error saving why subtitle.';
    }

   
    if ($section === 'features') {
        $ok = $about->save([
            'feature1_title' => trim((string)($_POST['feature1_title'] ?? '')),
            'feature1_text'  => trim((string)($_POST['feature1_text'] ?? '')),

            'feature2_title' => trim((string)($_POST['feature2_title'] ?? '')),
            'feature2_text'  => trim((string)($_POST['feature2_text'] ?? '')),

            'feature3_title' => trim((string)($_POST['feature3_title'] ?? '')),
            'feature3_text'  => trim((string)($_POST['feature3_text'] ?? '')),

            'feature4_title' => trim((string)($_POST['feature4_title'] ?? '')),
            'feature4_text'  => trim((string)($_POST['feature4_text'] ?? '')),
        ]);
        $message = $ok ? 'Features saved.' : 'Error saving features.';
    }
}


$current = $about->latest() ?? [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage About</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500&family=Playfair+Display:wght@600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="admin.css">
</head>

<body>
  <div class="admin-layout">

    <aside class="admin-sidebar">
      <h2 class="admin-logo">Leaf</h2>
      <ul class="admin-menu">
        <li><a href="admin.php">Dashboard</a></li>
        <li><a href="admin-homepage.php">Homepage</a></li>
        <li><a href="admin-products.php">Products</a></li>
        <li class="active"><a href="admin-about.php">About</a></li>
        <li><a href="admin-contact.php">Contact</a></li>
        <li><a href="admin-shipping.php">Shipping</a></li>
        <li><a href="admin-skintype.php">Skin Type</a></li>
        <li><a href="admin-users.php">Users</a></li>
         <li><a href="admin-orders.php">Orders</a></li>
         <li><a href="logout.php">Logout</a></li>
      </ul>
    </aside>

    <main class="admin-main">
      <h1>About Page</h1>

    
      <?php if ($message !== null): ?>
        <div class="manage-card" style="background:#eef; border:1px solid #cdd; border-radius:12px;">
          <?php echo htmlspecialchars($message); ?>
        </div>
        <br>
      <?php endif; ?>

     
      <form class="manage-card" method="POST">
        <h3>About Header</h3>
        <input type="hidden" name="section" value="header">

        <input
          type="text"
          name="about_subtitle"
          placeholder="Subtitle under About Leaf"
          value="<?php echo htmlspecialchars((string)($current['about_subtitle'] ?? '')); ?>"
        >

        <button class="btn-green" type="submit">Save</button>
      </form>

    
      <form class="manage-card" method="POST">
        <h3>Our Mission</h3>
        <input type="hidden" name="section" value="mission">

        <textarea name="mission_text" placeholder="Mission text" rows="6"><?php
          echo htmlspecialchars((string)($current['mission_text'] ?? ''));
        ?></textarea>

        <button class="btn-green" type="submit">Save</button>
      </form>

      
      <form class="manage-card" method="POST">
        <h3>Why choose Leaf</h3>
        <input type="hidden" name="section" value="why">

        <input
          type="text"
          name="why_subtitle"
          placeholder="Subtitle under Why choose Leaf"
          value="<?php echo htmlspecialchars((string)($current['why_subtitle'] ?? '')); ?>"
        >

        <button class="btn-green" type="submit">Save</button>
      </form>

     
      <form class="manage-card" method="POST">
        <h3>Features</h3>
        <input type="hidden" name="section" value="features">

        <input type="text" name="feature1_title" placeholder="Feature 1 title"
               value="<?php echo htmlspecialchars((string)($current['feature1_title'] ?? '')); ?>">
        <textarea name="feature1_text" placeholder="Feature 1 description" rows="3"><?php
          echo htmlspecialchars((string)($current['feature1_text'] ?? ''));
        ?></textarea>

        <input type="text" name="feature2_title" placeholder="Feature 2 title"
               value="<?php echo htmlspecialchars((string)($current['feature2_title'] ?? '')); ?>">
        <textarea name="feature2_text" placeholder="Feature 2 description" rows="3"><?php
          echo htmlspecialchars((string)($current['feature2_text'] ?? ''));
        ?></textarea>

        <input type="text" name="feature3_title" placeholder="Feature 3 title"
               value="<?php echo htmlspecialchars((string)($current['feature3_title'] ?? '')); ?>">
        <textarea name="feature3_text" placeholder="Feature 3 description" rows="3"><?php
          echo htmlspecialchars((string)($current['feature3_text'] ?? ''));
        ?></textarea>

        <input type="text" name="feature4_title" placeholder="Feature 4 title"
               value="<?php echo htmlspecialchars((string)($current['feature4_title'] ?? '')); ?>">
        <textarea name="feature4_text" placeholder="Feature 4 description" rows="3"><?php
          echo htmlspecialchars((string)($current['feature4_text'] ?? ''));
        ?></textarea>

        <button class="btn-green" type="submit">Save</button>
      </form>

    </main>
  </div>
</body>
</html>

