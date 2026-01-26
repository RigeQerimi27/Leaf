<?php

class Auth {

    public function __construct() {
     session_start();
    }

    
    public function login($email, $password) {

        
        if ($email == "admin@leaf.com" && $password == "12345678") {
         $_SESSION['user'] = $email;
         $_SESSION['role'] = "admin";
        return true;
        }

        
        if ($email == "user@leaf.com" && $password == "12341234") {

        $_SESSION['user'] = $email;
        $_SESSION['role'] = "user";
         return true;
        }
        return false;
    }

    
    public function check() {
        return isset($_SESSION['user']);
    }

    
    public function user() {
        return $_SESSION['user'] ?? null;
    }

    
    public function role() {
        return $_SESSION['role'] ?? null;
    }

    
    public function logout() {
        session_destroy();
    }
}


$auth = new Auth();

$error = null;


if (isset($_POST['login'])) {

    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

  
    $emailRegex = "/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/";
    $passwordRegex = "/^(?=.*\d).{8,}$/";

    if (!preg_match($emailRegex, $email)) {
        $error = "Email is not valid!";
    } elseif (!preg_match($passwordRegex, $password)) {
        $error = "Password must be at least 8 characters and contain a number!";
    } else {
        
        if ($auth->login($email, $password)) {

            if ($auth->role() == "admin") {
                header("Location: admin.html");
                exit;
            }

            if ($auth->role() == "user") {
                header("Location: homepage.html");
                exit;
            }

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
        <strong>Sign In</strong> <a href="signup.html" class="not-active tab">Sign Up</a>
     </p>

     <form class="form-box" id="loginForm" method="post">
        <label>Email Address:</label><br>
        <input type="email" id="email" name="email" placeholder="you@email.com"><br>

        <label>Password:</label><br>
        <input type="password" id="password" name="password" placeholder="********"><br>

        <p id="msg" style="color:red;">
            <?php if ($error) echo $error; ?>
        </p>

        
        <button class="Form-button" type="submit" name="login" onclick="validate()">Sign in</button>
     </form>
   </div>

   <script src="main.js"></script>


</body>
</html>