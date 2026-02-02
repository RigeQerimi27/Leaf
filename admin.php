<?php
declare(strict_types=1);

require_once __DIR__ . '/Database.php';
require_once __DIR__ . '/UserRepository.php';
require_once __DIR__ . '/AuthService.php';

$db = new Database();
$userRepo = new UserRepository($db);
$auth = new AuthService($userRepo);


$auth->requireAdmin();
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Leaf Admin Dashboard</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500&family=Playfair+Display:wght@600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="admin.css">
</head>
<body>

<div class="admin-layout">

  <aside class="admin-sidebar">
    <h2 class="admin-logo">Leaf</h2>
    <ul class="admin-menu">
      <li class="active"><a href="admin.php">Dashboard</a></li>
      <li><a href="admin-homepage.php">Homepage</a></li>
      <li><a href="admin-products.php">Products</a></li>
      <li><a href="admin-about.php">About</a></li>
      <li><a href="admin-contact.php">Contact</a></li>
      <li><a href="admin-shipping.php">Shipping</a></li>
      <li><a href="admin-skintype.php">Skin Type</a></li>
      <li><a href="admin-users.php">Users</a></li>
       <li><a href="admin-orders.php">Orders</a></li>
       <li><a href="logout.php">Logout</a></li>

    </ul>
  </aside>

  <main class="admin-main">

    

    <section class="stats">
      <div class="stat-card">
        <h3>Products</h3>
        <p>12</p>
      </div>
      <div class="stat-card">
        <h3>Orders</h3>
        <p>38</p>
      </div>
      <div class="stat-card">
        <h3>Messages</h3>
        <p>5</p>
      </div>
    </section>

    <section class="management">

      <div class="manage-card">
        <h3>Manage Homepage</h3>
        <p>Edit hero text, images & top picks.</p>
        <a class="btn-outline" href="admin-homepage.php">Edit</a>
      </div>

      <div class="manage-card">
        <h3>Manage About</h3>
        <p>Update story, values & images.</p>
        <a class="btn-outline" href="admin-about.php">Edit</a>
      </div>

      <div class="manage-card">
        <h3>Products</h3>
        <p>Add, edit or delete products.</p>
        <a class="btn-green" href="admin-products.php">Manage</a>
      </div>

      <div class="manage-card">
        <h3>Shipping</h3>
        <p>Shipping prices & zones.</p>
        <a class="btn-outline" href="admin-shipping.php">Edit</a>
      </div>

      <div class="manage-card">
        <h3>Manage Skin Type</h3>
        <p>Edit hero text, quiz question & benefits.</p>
        <a class="btn-outline" href="admin-skintype.php">Edit</a>
      </div>

      <div class="manage-card">
        <h3>Contact</h3>
        <p>Create contact info & view messages.</p>
        <a class="btn-outline" href="admin-contact.php">Edit</a>
      </div>

      <div class="manage-card">
  <h3>Users</h3>
  <p>Create , edit users & manage roles.</p>
  <a class="btn-outline" href="admin-users.php">Edit</a>
</div>

<div class="manage-card">
        <h3>Orders</h3>
        <p>Read & delete orders</p>
        <a class="btn-outline" href="admin-orders.php">Edit</a>
      </div>


    </section>

  </main>
</div>

</body>
</html>

