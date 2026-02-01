<?php
declare(strict_types=1);

session_start();

require_once __DIR__ . '/database.php';
require_once __DIR__ . '/Order.php';


$db = new Database();
$conn = $db->getConnection();

$orderModel = new Order($conn);


if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'delete') {
    $id = (int)($_POST['id'] ?? 0);

    if ($id > 0) {
        $ok = $orderModel->delete($id);
        $_SESSION['flash'] = $ok ? 'Order u fshi me sukses.' : 'S’u fshi dot order (provo prap).';
    }

    header('Location: admin-orders.php');
    exit;
}

$orders = $orderModel->allWithSummary();

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Orders</title>
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
      <li><a href="admin-about.php">About</a></li>
      <li><a href="admin-contact.php">Contact</a></li>
      <li><a href="admin-shipping.php">Shipping</a></li>
      <li><a href="admin-skintype.php">Skin Type</a></li>
      <li><a href="admin-users.php">Users</a></li>
      <li class="active"><a href="admin-orders.php">Orders</a></li>
      <li><a href="logout.php">Logout</a></li>
    </ul>
  </aside>

  <main class="admin-main">
    <div class="page">

      <header class="hero">
        <div>
          <p class="eyebrow">ADMINISTRATOR PANEL</p>
          <h1>ORDERS</h1>
          <p class="subtitle">View and delete customer orders</p>

          <?php if ($flash): ?>
            <p class="muted" style="margin-top:12px;">
              <?php echo htmlspecialchars($flash); ?>
            </p>
          <?php endif; ?>
        </div>
      </header>

      <section class="grid">

        <div class="card list-card">
          <h2>Orders List</h2>

          
          <div style="max-height: 520px; overflow-y: auto; padding-right: 10px;">

            <?php if (empty($orders)): ?>
              <p class="muted">No orders yet.</p>
            <?php endif; ?>

            <?php foreach ($orders as $o): ?>
              <div class="order-card" style="border-bottom:1px solid #eee; padding:16px 0;">
                <div class="order-top" style="display:flex; justify-content:space-between; align-items:center; margin-bottom:10px;">
                  <h3 style="font-family:'Playfair Display', serif; font-size:22px;">
                    Order #<?php echo (int)$o['id']; ?>
                  </h3>

                  <span class="order-status pending" style="padding:6px 16px; border-radius:20px; font-size:13px; font-weight:500;
                    background:#fff3cd; color:#856404;">
                    <?php echo htmlspecialchars((string)$o['status']); ?>
                  </span>
                </div>

                <div class="order-info" style="display:grid; grid-template-columns:1fr 1fr; gap:10px; font-size:14px; margin-bottom:14px;">
                  <p><strong>User:</strong> <?php echo htmlspecialchars((string)$o['created_by']); ?></p>
                  <p><strong>Date:</strong> <?php echo htmlspecialchars((string)($o['created_at'] ?? '')); ?></p>
                  <p><strong>Items:</strong> <?php echo (int)$o['items_count']; ?></p>
                  <p><strong>Total:</strong> €<?php echo number_format((float)$o['subtotal'], 2); ?></p>
                </div>

                <div class="order-actions">
                  
                  <form method="post" style="display:inline;" onsubmit="return confirm('Delete this order?');">
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="id" value="<?php echo (int)$o['id']; ?>">
                    <button type="submit" class="danger" style="background:none;border:none;padding:0;cursor:pointer;color:#c0392b;">
                      Delete
                    </button>
                  </form>
                </div>
              </div>
            <?php endforeach; ?>

          </div>
        </div>

      </section>

    </div>
  </main>

</div>
</body>
</html>

