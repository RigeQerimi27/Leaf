<?php
declare(strict_types=1);

session_start();

require_once __DIR__ . '/database.php';
require_once __DIR__ . '/SkintypePage.php';

$error = null;
$success = null;


$db = new Database();
$conn = $db->getConnection();


$page = new SkintypePage($conn);




$current = $page->latest();
if (!$current) {
    die('No data found in skintype_page table. Insert one row in phpMyAdmin.');
}


if (isset($_POST['save'])) {
    try {
        
        $id = (int)($_POST['id'] ?? 0);
        if ($id <= 0) {
            throw new RuntimeException("Invalid id.");
        }

       
        $data = [
            'hero_pill' => $_POST['hero_pill'] ?? '',
            'hero_title' => $_POST['hero_title'] ?? '',
            'hero_subtext' => $_POST['hero_subtext'] ?? '',

            'q1_text' => $_POST['q1_text'] ?? '',
            'q1_a_text' => $_POST['q1_a_text'] ?? '',
            'q1_a_type' => $_POST['q1_a_type'] ?? '',
            'q1_b_text' => $_POST['q1_b_text'] ?? '',
            'q1_b_type' => $_POST['q1_b_type'] ?? '',
            'q1_c_text' => $_POST['q1_c_text'] ?? '',
            'q1_c_type' => $_POST['q1_c_type'] ?? '',
            'q1_d_text' => $_POST['q1_d_text'] ?? '',
            'q1_d_type' => $_POST['q1_d_type'] ?? '',

            'q2_text' => $_POST['q2_text'] ?? '',
            'q2_a_text' => $_POST['q2_a_text'] ?? '',
            'q2_a_type' => $_POST['q2_a_type'] ?? '',
            'q2_b_text' => $_POST['q2_b_text'] ?? '',
            'q2_b_type' => $_POST['q2_b_type'] ?? '',
            'q2_c_text' => $_POST['q2_c_text'] ?? '',
            'q2_c_type' => $_POST['q2_c_type'] ?? '',
            'q2_d_text' => $_POST['q2_d_text'] ?? '',
            'q2_d_type' => $_POST['q2_d_type'] ?? '',

            'q3_text' => $_POST['q3_text'] ?? '',
            'q3_a_text' => $_POST['q3_a_text'] ?? '',
            'q3_a_type' => $_POST['q3_a_type'] ?? '',
            'q3_b_text' => $_POST['q3_b_text'] ?? '',
            'q3_b_type' => $_POST['q3_b_type'] ?? '',
            'q3_c_text' => $_POST['q3_c_text'] ?? '',
            'q3_c_type' => $_POST['q3_c_type'] ?? '',
            'q3_d_text' => $_POST['q3_d_text'] ?? '',
            'q3_d_type' => $_POST['q3_d_type'] ?? '',
        ];

       
        if (trim($data['hero_title']) === '') {
            throw new RuntimeException("Hero title cannot be empty.");
        }

       
        $page->update($id, $data);

       
        header("Location: admin-skintype.php?saved=1");
        exit;

    } catch (Throwable $e) {
        $error = "Save failed: " . $e->getMessage();
    }
}


if (isset($_GET['saved'])) {
    $success = "Skin Type page updated successfully!";
}


$current = $page->latest();


function e(string $value): string {
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Skin Type</title>
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
      <li><a href="admin-homepage.html">Homepage</a></li>
      <li><a href="admin-products.html">Products</a></li>
      <li><a href="admin-about.html">About</a></li>
      <li><a href="admin-contact.php">Contact</a></li>
      <li><a href="admin-shipping.html">Shipping</a></li>
      <li class="active"><a href="admin-skintype.php">Skin Type</a></li>
      <li><a href="admin-users.html">Users</a></li>
       <li><a href="admin-orders.html">Orders</a></li>
       <li><a href="logout.php">Logout</a></li>
    </ul>
  </aside>

  <main class="admin-main">
    <h1>Skin Type Page</h1>

    <?php if ($error): ?>
      <p style="color:red; font-weight:500;"><?= e($error) ?></p>
    <?php endif; ?>

    <?php if ($success): ?>
      <p style="color:green; font-weight:500;"><?= e($success) ?></p>
    <?php endif; ?>

   
    <form method="post" action="admin-skintype.php">
      <input type="hidden" name="id" value="<?= (int)$current['id'] ?>">

      <div class="manage-card">
        <h3>Hero Section</h3>
        <input type="text" name="hero_pill" placeholder="Hero pill" value="<?= e((string)$current['hero_pill']) ?>">
        <input type="text" name="hero_title" placeholder="Hero title" value="<?= e((string)$current['hero_title']) ?>">
        <textarea name="hero_subtext" placeholder="Hero subtext" style="width:100%; min-height:90px;"><?= e((string)$current['hero_subtext']) ?></textarea>
      </div>

      <br>

      <div class="manage-card">
        <h3>Quiz - Question 1</h3>
        <input type="text" name="q1_text" placeholder="Question 1 text" value="<?= e((string)$current['q1_text']) ?>">

        <input type="text" name="q1_a_text" placeholder="Option A text" value="<?= e((string)$current['q1_a_text']) ?>">
        <input type="text" name="q1_a_type" placeholder="Option A value/type (e.g. dry)" value="<?= e((string)$current['q1_a_type']) ?>">

        <input type="text" name="q1_b_text" placeholder="Option B text" value="<?= e((string)$current['q1_b_text']) ?>">
        <input type="text" name="q1_b_type" placeholder="Option B value/type (e.g. normal)" value="<?= e((string)$current['q1_b_type']) ?>">

        <input type="text" name="q1_c_text" placeholder="Option C text" value="<?= e((string)$current['q1_c_text']) ?>">
        <input type="text" name="q1_c_type" placeholder="Option C value/type (e.g. combination)" value="<?= e((string)$current['q1_c_type']) ?>">

        <input type="text" name="q1_d_text" placeholder="Option D text" value="<?= e((string)$current['q1_d_text']) ?>">
        <input type="text" name="q1_d_type" placeholder="Option D value/type (e.g. oily)" value="<?= e((string)$current['q1_d_type']) ?>">
      </div>

      <br>

      <div class="manage-card">
        <h3>Quiz - Question 2</h3>
        <input type="text" name="q2_text" placeholder="Question 2 text" value="<?= e((string)$current['q2_text']) ?>">

        <input type="text" name="q2_a_text" placeholder="Option A text" value="<?= e((string)$current['q2_a_text']) ?>">
        <input type="text" name="q2_a_type" placeholder="Option A value/type" value="<?= e((string)$current['q2_a_type']) ?>">

        <input type="text" name="q2_b_text" placeholder="Option B text" value="<?= e((string)$current['q2_b_text']) ?>">
        <input type="text" name="q2_b_type" placeholder="Option B value/type" value="<?= e((string)$current['q2_b_type']) ?>">

        <input type="text" name="q2_c_text" placeholder="Option C text" value="<?= e((string)$current['q2_c_text']) ?>">
        <input type="text" name="q2_c_type" placeholder="Option C value/type" value="<?= e((string)$current['q2_c_type']) ?>">

        <input type="text" name="q2_d_text" placeholder="Option D text" value="<?= e((string)$current['q2_d_text']) ?>">
        <input type="text" name="q2_d_type" placeholder="Option D value/type" value="<?= e((string)$current['q2_d_type']) ?>">
      </div>

      <br>

      <div class="manage-card">
        <h3>Quiz - Question 3</h3>
        <input type="text" name="q3_text" placeholder="Question 3 text" value="<?= e((string)$current['q3_text']) ?>">

        <input type="text" name="q3_a_text" placeholder="Option A text" value="<?= e((string)$current['q3_a_text']) ?>">
        <input type="text" name="q3_a_type" placeholder="Option A value/type" value="<?= e((string)$current['q3_a_type']) ?>">

        <input type="text" name="q3_b_text" placeholder="Option B text" value="<?= e((string)$current['q3_b_text']) ?>">
        <input type="text" name="q3_b_type" placeholder="Option B value/type" value="<?= e((string)$current['q3_b_type']) ?>">

        <input type="text" name="q3_c_text" placeholder="Option C text" value="<?= e((string)$current['q3_c_text']) ?>">
        <input type="text" name="q3_c_type" placeholder="Option C value/type" value="<?= e((string)$current['q3_c_type']) ?>">

        <input type="text" name="q3_d_text" placeholder="Option D text" value="<?= e((string)$current['q3_d_text']) ?>">
        <input type="text" name="q3_d_type" placeholder="Option D value/type" value="<?= e((string)$current['q3_d_type']) ?>">
      </div>

      <br>

      <button class="btn-green" type="submit" name="save">Save</button>
    </form>

  </main>
</div>
</body>
</html>


