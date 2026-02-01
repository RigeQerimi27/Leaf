<?php
declare(strict_types=1);

require_once __DIR__ . '/Database.php';
require_once __DIR__ . '/UserRepository.php';
require_once __DIR__ . '/AuthService.php';


$db = new Database();
$userRepo = new UserRepository($db);
$auth = new AuthService($userRepo);




$error = null;
$success = null;


$action = $_GET['action'] ?? 'list';
$id = isset($_GET['id']) ? (int)$_GET['id'] : null;


if ($action === 'create' && isset($_POST['create_user'])) {
    $fullName = trim($_POST['full_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $role = $_POST['role'] ?? 'user';

    
    if ($fullName === '' || $email === '' || $password === '' || !in_array($role, ['user', 'admin'], true)) {
        $error = "Please fill all fields correctly.";
    } else {
        try {
            $userRepo->create($fullName, $email, $password, $role);
            header("Location: admin-users.php?success=created");
            exit;
        } catch (RuntimeException $e) {
            $error = "Could not create user. Email might already exist.";
        }
    }
}


if ($action === 'edit' && $id && isset($_POST['update_user'])) {
    $fullName = trim($_POST['full_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $role = $_POST['role'] ?? 'user';

   
    $newPassword = trim($_POST['new_password'] ?? '');

    if ($fullName === '' || $email === '' || !in_array($role, ['user', 'admin'], true)) {
        $error = "Please fill all fields correctly.";
    } else {
        try {
            $userRepo->update($id, $fullName, $email, $role);

           
            if ($newPassword !== '') {
              
                if (strlen($newPassword) < 8 || !preg_match('/\d/', $newPassword)) {
                    $error = "New password must be at least 8 characters and contain a number.";
                } else {
                    $userRepo->updatePassword($id, $newPassword);
                }
            }

            if (!$error) {
                header("Location: admin-users.php?success=updated");
                exit;
            }
        } catch (RuntimeException $e) {
            $error = "Could not update user. Email might already exist.";
        }
    }
}


if ($action === 'delete' && $id && isset($_POST['confirm_delete'])) {
    
    if ($auth->id() === $id) {
        $error = "You cannot delete your own admin account while logged in.";
    } else {
        try {
            $userRepo->delete($id);
            header("Location: admin-users.php?success=deleted");
            exit;
        } catch (RuntimeException $e) {
            $error = "Could not delete user.";
        }
    }
}


$users = $userRepo->all();


if (isset($_GET['success'])) {
    $map = [
        'created' => 'User created successfully.',
        'updated' => 'User updated successfully.',
        'deleted' => 'User deleted successfully.',
    ];
    $success = $map[$_GET['success']] ?? null;
}


$editUser = null;
if ($action === 'edit' && $id) {
    $editUser = $userRepo->findById($id);
    if (!$editUser) {
        $error = "User not found.";
        $action = 'list';
    }
}


$deleteUser = null;
if ($action === 'delete' && $id) {
    $deleteUser = $userRepo->findById($id);
    if (!$deleteUser) {
        $error = "User not found.";
        $action = 'list';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Users</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500&family=Playfair+Display:wght@600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="admin.css">
  <link rel="stylesheet" href="admin-crud.css">
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
      <li><a href="admin-contact.html">Contact</a></li>
      <li><a href="admin-shipping.html">Shipping</a></li>
      <li><a href="admin-skintype.html">Skin Type</a></li>
      <li class="active"><a href="admin-users.php">Users</a></li>
      <li><a href="admin-orders.html">Orders</a></li>

      <!-- Logout link -->
      <li><a href="logout.php">Logout</a></li>
    </ul>
  </aside>

  <main class="admin-main">
    <div class="page">

      <header class="hero">
        <div>
          <p class="eyebrow">ADMINISTRATOR PANEL</p>
          <h1>USERS</h1>
          <p class="subtitle">Create and manage system users</p>
        </div>
      </header>

      
      <?php if ($error): ?>
        <div class="card" style="border:1px solid #f2b8b5; padding:12px; margin-bottom:16px;">
          <strong style="color:#b00020;"><?php echo htmlspecialchars($error); ?></strong>
        </div>
      <?php endif; ?>

      
      <?php if ($success): ?>
        <div class="card" style="border:1px solid #b7e4c7; padding:12px; margin-bottom:16px;">
          <strong><?php echo htmlspecialchars($success); ?></strong>
        </div>
      <?php endif; ?>

      <section class="grid">

      
        <div class="card form-card">

          <?php if ($action === 'edit' && $editUser): ?>
            <h2>Edit User</h2>
            <p class="muted">Update user details and role</p>

            <form class="product-form" method="post" action="admin-users.php?action=edit&id=<?php echo (int)$editUser['id']; ?>">
              <label>
                Full Name
                <input type="text" name="full_name" value="<?php echo htmlspecialchars($editUser['full_name']); ?>" required>
              </label>

              <label>
                Email
                <input type="email" name="email" value="<?php echo htmlspecialchars($editUser['email']); ?>" required>
              </label>

              <label>
                Role
                <select name="role" required>
                  <option value="user" <?php echo $editUser['role']==='user'?'selected':''; ?>>User</option>
                  <option value="admin" <?php echo $editUser['role']==='admin'?'selected':''; ?>>Admin</option>
                </select>
              </label>

              <label>
                New Password (optional)
                <input type="password" name="new_password" placeholder="Leave empty to keep current">
              </label>

              <button type="submit" class="primary" name="update_user">Save changes</button>
              <a class="danger" href="admin-users.php" style="margin-left:10px;">Cancel</a>
            </form>

          <?php elseif ($action === 'delete' && $deleteUser): ?>
            <h2>Delete User</h2>
            <p class="muted">This action cannot be undone.</p>

            <div style="margin: 12px 0;">
              <strong><?php echo htmlspecialchars($deleteUser['full_name']); ?></strong><br>
              <?php echo htmlspecialchars($deleteUser['email']); ?><br>
              Role: <?php echo htmlspecialchars($deleteUser['role']); ?>
            </div>

            <form method="post" action="admin-users.php?action=delete&id=<?php echo (int)$deleteUser['id']; ?>">
              <button type="submit" class="danger" name="confirm_delete">Yes, delete</button>
              <a class="primary" href="admin-users.php" style="margin-left:10px;">Cancel</a>
            </form>

          <?php else: ?>
            <h2>Create User</h2>
            <p class="muted">Add a new user or admin</p>

            <form class="product-form" method="post" action="admin-users.php?action=create">
              <label>
                Full Name
                <input type="text" name="full_name" placeholder="John Doe" required>
              </label>

              <label>
                Email
                <input type="email" name="email" placeholder="user@leaf.com" required>
              </label>

              <label>
                Password
                <input type="password" name="password" placeholder="********" required>
              </label>

              <label>
                Role
                <select name="role" required>
                  <option value="user">User</option>
                  <option value="admin">Admin</option>
                </select>
              </label>

              <button type="submit" class="primary" name="create_user">Create user</button>
            </form>
          <?php endif; ?>

        </div>

    
        <div class="card list-card">
  <h2>User List</h2>

  <div style="max-height: 420px; overflow-y: auto; padding-right: 10px;">
    <?php foreach ($users as $u): ?>
      <div class="product-item">
        <h3><?php echo htmlspecialchars($u['full_name']); ?></h3>
        <p><?php echo htmlspecialchars($u['email']); ?></p>
        <p>Role: <?php echo htmlspecialchars(ucfirst($u['role'])); ?></p>

        <a href="admin-users.php?action=edit&id=<?php echo (int)$u['id']; ?>">Edit</a>
        <a class="danger" href="admin-users.php?action=delete&id=<?php echo (int)$u['id']; ?>">Delete</a>
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

