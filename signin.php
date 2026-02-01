<?php
declare(strict_types=1);


require_once __DIR__ . '/Database.php';
require_once __DIR__ . '/UserRepository.php';
require_once __DIR__ . '/AuthService.php';


$db = new Database();
$userRepo = new UserRepository($db);
$auth = new AuthService($userRepo);


$error = null;


if (isset($_POST['login'])) {
    
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

   
    $emailRegex = "/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/";

   
    if (!preg_match($emailRegex, $email)) {
        $error = "Email is not valid!";
    } elseif ($password === '') {
        $error = "Password is required!";
    } else {
       
        if ($auth->login($email, $password)) {
           
            if ($auth->role() === 'admin') {
                header("Location: admin.php");
                exit;
            }

            
            header("Location: homepage.php");
            exit;
        } else {
            $error = "Email ose password gabim!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sign in - Leaf</title>

  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500&family=Playfair+Display:wght@600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="loginforms.css">
</head>
<body class="page">

<div class="card">
  <h1 class="main-title">Welcome to Leaf</h1>
  <p class="subtitle">Sign in to your account or create a new one</p>

  <p class="tabs">
    <strong>Sign In</strong>
    <a href="signup.php" class="not-active tab">Sign Up</a>
  </p>

  <form class="form-box" method="post">
    <label>Email Address:</label><br>
    <input type="email" name="email" placeholder="you@email.com" required><br>

    <label>Password:</label><br>
    <input type="password" name="password" placeholder="********" required><br>

    <p id="msg" style="color:red;">
      <?php if ($error) echo htmlspecialchars($error); ?>
    </p>

    <button class="Form-button" type="submit" name="login">Sign in</button>
  </form>
</div>

<script src="main.js"></script>
</body>
</html>
