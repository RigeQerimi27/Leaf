<?php
class Auth {
    public function __construct() {
        session_start();
    }

    
    public function register($email, $password) {

        if (!isset($_SESSION['users'])) {
            $_SESSION['users'] = [];
        }

       
        if (isset($_SESSION['users'][$email])) {
            return false;
        }

        
        $_SESSION['users'][$email] = [
            "password" => $password,
            "role" => "user"
        ];
 
        $_SESSION['user'] = $email;
        $_SESSION['role'] = "user";

        return true;
    }
}

$auth = new Auth();
$error = null;

if (isset($_POST['signup'])) {
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    
    $emailRegex = "/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/";
    $passwordRegex = "/^(?=.*\d).{8,}$/";

    if (!preg_match($emailRegex, $email)) {
        $error = "Email is not valid!";
    } elseif (!preg_match($passwordRegex, $password)) {
        $error = "Password must be at least 8 characters and contain a number!";
    } else {
        if ($auth->register($email, $password)) {
            header("Location: homepage.html");
            exit;
        } else {
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
      <a href="signin.php" class="not-active tab">Sign In</a><strong>Sign Up</strong>
    </p>

    
    <form class="form-box" id="loginForm" method="post" novalidate>
      <label>Email Address:</label><br>


      <input type="email" id="email" name="email" placeholder="you@email.com"><br>

      <label>Password:</label><br>
      <input type="password" id="password" name="password" placeholder="********"><br>

      <p id="msg" style="color:red;">
        <?php if ($error) echo $error; ?>
      </p>

     
      <button class="Form-button" type="submit" name="signup" onclick="return validate()">
        Sign up
      </button>
    </form>
   </div>

   <script src="main.js"></script>
</body>
</html>
