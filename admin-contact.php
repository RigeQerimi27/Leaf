<?php
declare(strict_types=1);

session_start();

require_once __DIR__ . '/database.php';       
require_once __DIR__ . '/contactmessage.php'; 

$error = null;
$success = null;


$db = new Database();
$conn = $db->getConnection();


$contactModel = new ContactMessage($conn);




if (isset($_POST['create_message'])) {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');

    
    $emailRegex = "/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/";
    $nameRegex = "/^[A-Za-z\s]{3,}$/";

    if (!preg_match($emailRegex, $email)) {
        $error = "Email is not valid!";
    } elseif (!preg_match($nameRegex, $name)) {
        $error = "Name must be at least 3 letters!";
    } elseif ($subject === '' || $message === '') {
        $error = "Subject and message are required!";
    } else {
        try {
            
            $createdBy = $_SESSION['user'] ?? 'admin';

            $contactModel->create($name, $email, $subject, $message, $createdBy);

            
            header("Location: admin-contact.php?success=1");
            exit;
        } catch (Throwable $e) {
            $error = "Database error: " . $e->getMessage();
        }
    }
}


if (isset($_POST['delete_message'])) {
    $id = (int)($_POST['id'] ?? 0);

    if ($id <= 0) {
        $error = "Invalid message id.";
    } else {
        try {
            $contactModel->delete($id);
            header("Location: admin-contact.php?deleted=1");
            exit;
        } catch (Throwable $e) {
            $error = "Delete failed: " . $e->getMessage();
        }
    }
}


if (isset($_GET['success'])) {
    $success = "Message created successfully!";
}
if (isset($_GET['deleted'])) {
    $success = "Message deleted successfully!";
}

$messages = $contactModel->all();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Contact Management</title>

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
      <li><a href="admin-about.php">About</a></li>
      <li class="active"><a href="admin-contact.php">Contact</a></li>
      <li><a href="admin-shipping.php">Shipping</a></li>
      <li><a href="admin-skintype.php">Skin Type</a></li>
      <li><a href="admin-users.php">Users</a></li>
       <li><a href="admin-orders.php">Orders</a></li>
       <li><a href="logout.php">Logout</a></li>
    </ul>
  </aside>

  <main class="admin-main">
    <h1>Contact</h1>

   
    <?php if ($error): ?>
      <p style="color:red; font-weight:500;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <?php if ($success): ?>
      <p style="color:green; font-weight:500;"><?= htmlspecialchars($success) ?></p>
    <?php endif; ?>

   
    <div class="manage-card">
      <h3>Create New Contact Message</h3>

      
      <form method="post" action="admin-contact.php">
        <input type="text" name="name" placeholder="Name" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="text" name="subject" placeholder="Subject" required>

        <textarea
          name="message"
          placeholder="Message"
          required
          style="width:100%; min-height:120px;"
        ></textarea>

        <button class="btn-green" type="submit" name="create_message">Create</button>
      </form>
    </div>

  
    <div class="manage-card">
      <h3>Messages</h3>

      <?php if (empty($messages)): ?>
        <p>No messages found.</p>
      <?php else: ?>
        <?php foreach ($messages as $m): ?>
          <div class="product-item">

           
            <p><strong>ID:</strong> <?= (int)$m['id'] ?></p>
            <p><strong>Name:</strong> <?= htmlspecialchars($m['name']) ?></p>
            <p><strong>Email:</strong> <?= htmlspecialchars($m['email']) ?></p>
            <p><strong>Subject:</strong> <?= htmlspecialchars($m['subject']) ?></p>
            <p><strong>Message:</strong> <?= nl2br(htmlspecialchars($m['message'])) ?></p>
            <p><strong>Created By:</strong> <?= htmlspecialchars($m['created_by']) ?></p>
            <p><strong>Created At:</strong> <?= htmlspecialchars((string)$m['created_at']) ?></p>

           
            <form method="post" action="admin-contact.php" onsubmit="return confirm('Delete this message?');">
              <input type="hidden" name="id" value="<?= (int)$m['id'] ?>">
              <button type="submit" name="delete_message" class="btn-green" style="background:#c0392b;">
                Delete
              </button>
            </form>

          </div>
        <?php endforeach; ?>
      <?php endif; ?>

    </div>

  </main>
</div>
</body>
</html>


