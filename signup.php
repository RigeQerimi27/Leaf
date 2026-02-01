<?php
declare(strict_types=1);

require_once __DIR__ . '/Database.php';
require_once __DIR__ . '/UserRepository.php';
require_once __DIR__ . '/AuthService.php';

$db = new Database();
$userRepo = new UserRepository($db);
$auth = new AuthService($userRepo);

$error = null;

if (isset($_POST['signup'])) {
    $fullName = trim($_POST['full_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    $emailRegex = "/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/";
    $passwordRegex = "/^(?=.*\d).{8,}$/"; 

    if ($fullName === '') {
        $error = "Full name is required!";
    } elseif (!preg_match($emailRegex, $email)) {
        $error = "Email is not valid!";
    } elseif (!preg_match($passwordRegex, $password)) {
        $error = "Password must be at least 8 characters and contain a number!";
    } else {
        try {
           
            $userRepo->create($fullName, $email, $password, 'user');

          
            $auth->login($email, $password);

            header("Location: homepage.php");
            exit;
        } catch (RuntimeException $e) {
           
            $error = "This email is already registered!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sign up - Leaf</title>

  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500&family=Playfair+Display:wght@600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="loginforms.css">
</head>
<body class="page">

<div class="card">
  <h1 class="main-title">Welcome to Leaf</h1>
  <p class="subtitle">Sign in to your account or create a new one</p>

  <p class="tabs">
    <a href="signin.php" class="not-active tab">Sign In</a>
    <strong>Sign Up</strong>
  </p>

  <form class="form-box" method="post" novalidate>
    <label>Full Name:</label><br>
    <input type="text" name="full_name" placeholder="John Doe" required><br>

    <label>Email Address:</label><br>
    <input type="email" name="email" placeholder="you@email.com" required><br>

    <label>Password:</label><br>
    <input type="password" name="password" placeholder="********" required><br>

    <p id="msg" style="color:red;">
      <?php if ($error) echo htmlspecialchars($error); ?>
    </p>

    <button class="Form-button" type="submit" name="signup">Sign up</button>
  </form>
</div>

<script src="main.js"></script>
</body>
</html>

