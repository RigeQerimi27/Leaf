<?php
declare(strict_types=1);


session_start();

require_once __DIR__ . '/database.php';
require_once __DIR__ . '/HomepageContent.php';


$db   = new Database();
$conn = $db->getConnection();


$homepage = new HomepageContent($conn);


$message = null;


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $section = (string)($_POST['section'] ?? '');

   
    if ($section === 'hero') {
        $ok = $homepage->save([
            'hero_title' => trim((string)($_POST['hero_title'] ?? '')),
            'hero_sub'   => trim((string)($_POST['hero_sub'] ?? '')),
        ]);

        $message = $ok ? 'Hero section saved successfully.' : 'Error saving Hero section.';
    }

    
    if ($section === 'skin') {
        $ok = $homepage->save([
            'skin_title'      => trim((string)($_POST['skin_title'] ?? '')),
            'skin_text'       => trim((string)($_POST['skin_text'] ?? '')),
            'skin_btn_text'   => trim((string)($_POST['skin_btn_text'] ?? '')),
            'skin_btn_link'   => trim((string)($_POST['skin_btn_link'] ?? '')),
            'skin_info_title' => trim((string)($_POST['skin_info_title'] ?? '')),
            'skin_info_text'  => trim((string)($_POST['skin_info_text'] ?? '')),
        ]);

        $message = $ok ? 'Skin Type section saved successfully.' : 'Error saving Skin Type section.';
    }

    
    if ($section === 'top_picks') {
        $ok = $homepage->save([
            'top_picks_title' => trim((string)($_POST['top_picks_title'] ?? '')),
            'top_picks_sub'   => trim((string)($_POST['top_picks_sub'] ?? '')),
        ]);

        $message = $ok ? 'Top Picks section saved successfully.' : 'Error saving Top Picks section.';
    }

    
    if ($section === 'cards') {
        $ok = $homepage->save([
            'card1_title' => trim((string)($_POST['card1_title'] ?? '')),
            'card1_text'  => trim((string)($_POST['card1_text'] ?? '')),
            'card1_link'  => trim((string)($_POST['card1_link'] ?? '')),

            'card2_title' => trim((string)($_POST['card2_title'] ?? '')),
            'card2_text'  => trim((string)($_POST['card2_text'] ?? '')),
            'card2_link'  => trim((string)($_POST['card2_link'] ?? '')),

            'card3_title' => trim((string)($_POST['card3_title'] ?? '')),
            'card3_text'  => trim((string)($_POST['card3_text'] ?? '')),
            'card3_link'  => trim((string)($_POST['card3_link'] ?? '')),
        ]);

        $message = $ok ? 'Info Cards saved successfully.' : 'Error saving Info Cards.';
    }
}


$current = $homepage->latest() ?? [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manage Homepage</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500&family=Playfair+Display:wght@600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="admin.css">
</head>

<body>
<div class="admin-layout">

  <aside class="admin-sidebar">
    <h2 class="admin-logo">Leaf</h2>
    <ul class="admin-menu">
      <li><a href="admin.html">Dashboard</a></li>
      <li class="active"><a href="admin-homepage.php">Homepage</a></li>
      <li><a href="admin-products.html">Products</a></li>
      <li><a href="admin-about.html">About</a></li>
      <li><a href="admin-contact.html">Contact</a></li>
      <li><a href="admin-shipping.html">Shipping</a></li>
      <li><a href="admin-skintype.html">Skin Type</a></li>
      <li><a href="admin-users.html">Users</a></li>
    </ul>
  </aside>

  <main class="admin-main">
    <h1>Homepage Management</h1>

  
    <?php if ($message !== null): ?>
      <div class="manage-card" style="background:#eef; border:1px solid #cdd; border-radius:12px;">
        <?php echo htmlspecialchars($message); ?>
      </div>
      <br>
    <?php endif; ?>

    
    <form class="manage-card" method="POST">
      <h3>Hero Section</h3>
      <input type="hidden" name="section" value="hero">

      <input
        type="text"
        name="hero_title"
        placeholder="Hero title"
        value="<?php echo htmlspecialchars((string)($current['hero_title'] ?? '')); ?>"
      >

      <input
        type="text"
        name="hero_sub"
        placeholder="Hero subtitle"
        value="<?php echo htmlspecialchars((string)($current['hero_sub'] ?? '')); ?>"
      >

     
      <button class="btn-green" type="submit">Save</button>
    </form>

    <br>

    
    <form class="manage-card" method="POST">
      <h3>Skin Type Section</h3>
      <input type="hidden" name="section" value="skin">

      <input
        type="text"
        name="skin_title"
        placeholder="Title"
        value="<?php echo htmlspecialchars((string)($current['skin_title'] ?? '')); ?>"
      >

      <input
        type="text"
        name="skin_text"
        placeholder="Text"
        value="<?php echo htmlspecialchars((string)($current['skin_text'] ?? '')); ?>"
      >

      <input
        type="text"
        name="skin_btn_text"
        placeholder="Button text"
        value="<?php echo htmlspecialchars((string)($current['skin_btn_text'] ?? '')); ?>"
      >

      <input
        type="text"
        name="skin_btn_link"
        placeholder="Button link"
        value="<?php echo htmlspecialchars((string)($current['skin_btn_link'] ?? '')); ?>"
      >

      <input
        type="text"
        name="skin_info_title"
        placeholder="Info title"
        value="<?php echo htmlspecialchars((string)($current['skin_info_title'] ?? '')); ?>"
      >

      <input
        type="text"
        name="skin_info_text"
        placeholder="Info text"
        value="<?php echo htmlspecialchars((string)($current['skin_info_text'] ?? '')); ?>"
      >

      <button class="btn-green" type="submit">Save</button>
    </form>

    <br>

   
    <form class="manage-card" method="POST">
      <h3>Top Picks Section</h3>
      <input type="hidden" name="section" value="top_picks">

      <input
        type="text"
        name="top_picks_title"
        placeholder="Section title"
        value="<?php echo htmlspecialchars((string)($current['top_picks_title'] ?? '')); ?>"
      >

      <input
        type="text"
        name="top_picks_sub"
        placeholder="Section subtitle"
        value="<?php echo htmlspecialchars((string)($current['top_picks_sub'] ?? '')); ?>"
      >

      <button class="btn-green" type="submit">Save</button>
    </form>

    <br>

  
    <form class="manage-card" method="POST">
      <h3>Info Cards</h3>
      <input type="hidden" name="section" value="cards">

      <input
        type="text"
        name="card1_title"
        placeholder="Card 1 title"
        value="<?php echo htmlspecialchars((string)($current['card1_title'] ?? '')); ?>"
      >
      <input
        type="text"
        name="card1_text"
        placeholder="Card 1 text"
        value="<?php echo htmlspecialchars((string)($current['card1_text'] ?? '')); ?>"
      >
      <input
        type="text"
        name="card1_link"
        placeholder="Card 1 link"
        value="<?php echo htmlspecialchars((string)($current['card1_link'] ?? '')); ?>"
      >

      <br><br>

      <input
        type="text"
        name="card2_title"
        placeholder="Card 2 title"
        value="<?php echo htmlspecialchars((string)($current['card2_title'] ?? '')); ?>"
      >
      <input
        type="text"
        name="card2_text"
        placeholder="Card 2 text"
        value="<?php echo htmlspecialchars((string)($current['card2_text'] ?? '')); ?>"
      >
      <input
        type="text"
        name="card2_link"
        placeholder="Card 2 link"
        value="<?php echo htmlspecialchars((string)($current['card2_link'] ?? '')); ?>"
      >

      <br><br>

      <input
        type="text"
        name="card3_title"
        placeholder="Card 3 title"
        value="<?php echo htmlspecialchars((string)($current['card3_title'] ?? '')); ?>"
      >
      <input
        type="text"
        name="card3_text"
        placeholder="Card 3 text"
        value="<?php echo htmlspecialchars((string)($current['card3_text'] ?? '')); ?>"
      >
      <input
        type="text"
        name="card3_link"
        placeholder="Card 3 link"
        value="<?php echo htmlspecialchars((string)($current['card3_link'] ?? '')); ?>"
      >

      <button class="btn-green" type="submit">Save</button>
    </form>

  </main>
</div>
</body>
</html>



