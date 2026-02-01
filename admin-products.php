<?php
declare(strict_types=1);

session_start();

require_once __DIR__ . '/database.php';
require_once __DIR__ . '/Product.php';

$db = new Database();
$conn = $db->getConnection();
$productModel = new Product($conn);



$editId = isset($_GET['edit']) ? (int)$_GET['edit'] : 0;
$editProduct = $editId > 0 ? $productModel->findById($editId) : null;

$products = $productModel->all();
$totalProducts = $productModel->countAll();

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

$dbName = getenv('DB_NAME') ?: 'projekti_web';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Products</title>
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
        <li class="active"><a href="admin-products.php">Products</a></li>
        <li><a href="admin-about.html">About</a></li>
        <li><a href="admin-contact.html">Contact</a></li>
        <li><a href="admin-shipping.html">Shipping</a></li>
        <li><a href="admin-skintype.html">Skin Type</a></li>
        <li><a href="admin-users.html">Users</a></li>
        <li><a href="admin-orders.html">Orders</a></li>
        <li><a href="logout.php">Logout</a></li>
      </ul>
    </aside>

    <main class="admin-main">
      <div class="page">

        <header class="hero">
          <div>
            <p class="eyebrow">ADMINISTRATOR PANEL</p>
            <h1>ADMIN</h1>
            <p class="subtitle">Product management for Leaf Skincare</p>

            <?php if ($flash): ?>
              <p class="muted" style="margin-top:12px;">
                <?php echo htmlspecialchars($flash); ?>
              </p>
            <?php endif; ?>
          </div>

          <div class="card stats">
            <div>
              <span class="stat-label">Total products</span>
              <span class="stat-value"><?php echo (int)$totalProducts; ?></span>
            </div>
            <div>
              <span class="stat-label">Database</span>
              <span class="stat-value"><?php echo htmlspecialchars($dbName); ?></span>
            </div>
          </div>
        </header>

        <section class="grid">

          
          <div class="card form-card">
            <h2><?php echo $editProduct ? 'Edit product' : 'Add a new product'; ?></h2>
            <p class="muted"><?php echo $editProduct ? 'Update the product details' : 'Fill in the product details'; ?></p>

            <form class="product-form" method="post" action="admin-products-handler.php">
              <input type="hidden" name="action" value="<?php echo $editProduct ? 'update' : 'create'; ?>">
              <?php if ($editProduct): ?>
                <input type="hidden" name="id" value="<?php echo (int)$editProduct['id']; ?>">
              <?php endif; ?>

              <label>
                Product Name
                <input type="text" name="name" placeholder="Product Name" required
                  value="<?php echo htmlspecialchars((string)($editProduct['name'] ?? '')); ?>">
              </label>

              <label>
                Description
                <textarea name="description" rows="4" placeholder="Description" required><?php
                  echo htmlspecialchars((string)($editProduct['description'] ?? ''));
                ?></textarea>
              </label>

              <div class="two-columns">
                <label>
                  Size
                  <input type="text" name="size" placeholder="300 ml"
                    value="<?php echo htmlspecialchars((string)($editProduct['size'] ?? '')); ?>">
                </label>

                <label>
                  Benefit
                  <input type="text" name="benefit" placeholder="Stress relief"
                    value="<?php echo htmlspecialchars((string)($editProduct['benefit'] ?? '')); ?>">
                </label>
              </div>

              <div class="two-columns">
                <label>
                  Shop Page
                  <?php $shopPageVal = (string)($editProduct['shop_page'] ?? ''); ?>
                  <select name="shop_page" required>
                    <option value="">Select</option>
                    <option value="bestsellers" <?php echo $shopPageVal === 'bestsellers' ? 'selected' : ''; ?>>Best sellers</option>
                    <option value="skincare" <?php echo $shopPageVal === 'skincare' ? 'selected' : ''; ?>>Skincare</option>
                    <option value="bodycare" <?php echo $shopPageVal === 'bodycare' ? 'selected' : ''; ?>>Body care</option>
                    <option value="bath-relax" <?php echo $shopPageVal === 'bath-relax' ? 'selected' : ''; ?>>Bath &amp; Relax</option>
                  </select>
                </label>

                <label>
                  Section
                  <?php $sectionVal = (string)($editProduct['shop_section'] ?? ''); ?>
                  <select name="shop_section" required>
                    <option value="">Select</option>
                    <option value="on-sale" <?php echo $sectionVal === 'on-sale' ? 'selected' : ''; ?>>On Sale</option>

                    <option value="bestsellers-skincare" <?php echo $sectionVal === 'bestsellers-skincare' ? 'selected' : ''; ?>>Bestsellers - Skincare</option>
                    <option value="bestsellers-bodycare" <?php echo $sectionVal === 'bestsellers-bodycare' ? 'selected' : ''; ?>>Bestsellers - Body care</option>
                    <option value="bestsellers-bath-relax" <?php echo $sectionVal === 'bestsellers-bath-relax' ? 'selected' : ''; ?>>Bestsellers - Bath &amp; Relax</option>

                    <option value="cleansers" <?php echo $sectionVal === 'cleansers' ? 'selected' : ''; ?>>Cleansers</option>
                    <option value="serums" <?php echo $sectionVal === 'serums' ? 'selected' : ''; ?>>Serums</option>
                    <option value="moisturizers" <?php echo $sectionVal === 'moisturizers' ? 'selected' : ''; ?>>Moisturizers</option>

                    <option value="body-lotions" <?php echo $sectionVal === 'body-lotions' ? 'selected' : ''; ?>>Body Lotions</option>
                    <option value="body-scrubs" <?php echo $sectionVal === 'body-scrubs' ? 'selected' : ''; ?>>Body scrubs</option>
                    <option value="body-oils" <?php echo $sectionVal === 'body-oils' ? 'selected' : ''; ?>>Body Oils</option>

                    <option value="bath-soaks" <?php echo $sectionVal === 'bath-soaks' ? 'selected' : ''; ?>>Bath Soaks</option>
                    <option value="bath-foams" <?php echo $sectionVal === 'bath-foams' ? 'selected' : ''; ?>>Bath Foams</option>
                    <option value="aromatherapy-mists" <?php echo $sectionVal === 'aromatherapy-mists' ? 'selected' : ''; ?>>Aromatherapy Mists</option>
                  </select>
                </label>
              </div>

              <div class="two-columns">
                <label>
                  Price (€)
                  <input type="number" name="price" step="0.01" placeholder="25.00" required
                    value="<?php echo htmlspecialchars((string)($editProduct['price'] ?? '')); ?>">
                </label>

                <label>
                  Stock
                  <input type="number" name="stock" placeholder="10" required
                    value="<?php echo htmlspecialchars((string)($editProduct['stock'] ?? '')); ?>">
                </label>
              </div>

              <div class="two-columns">
                <label>
                  Old Price (€)
                  <input type="number" name="old_price" step="0.01" placeholder="32.00"
                    value="<?php echo htmlspecialchars((string)($editProduct['old_price'] ?? '')); ?>">
                </label>

                <label>
                  Discount %
                  <input type="number" name="discount_percent" step="1" placeholder="20"
                    value="<?php echo htmlspecialchars((string)($editProduct['discount_percent'] ?? '')); ?>">
                </label>
              </div>

              <label>
                Image path (from images/)
                <input type="text" name="image_path" placeholder="images/shop images/skincare/cream.jpg"
                  value="<?php echo htmlspecialchars((string)($editProduct['image_path'] ?? '')); ?>">
              </label>

              <button type="submit" class="primary">
                <?php echo $editProduct ? 'Update product' : 'Create product'; ?>
              </button>

              <?php if ($editProduct): ?>
                <p class="muted" style="margin-top:12px;">
                  <a href="admin-products.php" style="color:#2F9E4E; text-decoration:none;">Cancel edit</a>
                </p>
              <?php endif; ?>
            </form>
          </div>

          
          <div class="card list-card">
            <h2>Product List</h2>

            <div style="max-height: 420px; overflow-y: auto; padding-right: 10px;">
              <?php if (empty($products)): ?>
                <p class="muted">No products yet.</p>
              <?php endif; ?>

              <?php foreach ($products as $p): ?>
                <div class="product-item">
                  <h3><?php echo htmlspecialchars((string)$p['name']); ?></h3>
                  <p><?php echo htmlspecialchars((string)$p['description']); ?></p>
                  <p>€<?php echo number_format((float)$p['price'], 2); ?> | <?php echo (int)$p['stock']; ?> in stock</p>
                  <p class="muted"><?php echo htmlspecialchars((string)($p['shop_page'] . ' / ' . $p['shop_section'])); ?></p>

                  <a href="admin-products.php?edit=<?php echo (int)$p['id']; ?>">Edit</a>

                  <form method="post" action="admin-products-handler.php" style="display:inline;"
                        onsubmit="return confirm('Delete this product?');">
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="id" value="<?php echo (int)$p['id']; ?>">
                    <button type="submit" class="danger" style="background:none;border:none;padding:0;cursor:pointer;">
                      Delete
                    </button>
                  </form>
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


