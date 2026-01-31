<?php
declare(strict_types=1);

session_start();

require_once __DIR__ . '/SkinTypeCalculator.php';


$q1 = $_POST['q1'] ?? '';
$q2 = $_POST['q2'] ?? '';
$q3 = $_POST['q3'] ?? '';

if ($q1 === '' || $q2 === '' || $q3 === '') {
    header('Location: skintype.php');
    exit;
}

$calc = new SkinTypeCalculator();        
$type = $calc->calculate($q1, $q2, $q3);  

$_SESSION['skin_type'] = $type;          

$title = $calc->label($type);            
$text  = $calc->description($type);      
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?php echo htmlspecialchars($title); ?></title>
  <link rel="stylesheet" href="skintype.css">
</head>
<body>

  <div class="st-quiz-card" style="max-width: 700px; margin: 60px auto;">
    <h2 class="st-quiz-question"><?php echo htmlspecialchars($title); ?></h2>
    <p><?php echo htmlspecialchars($text); ?></p>

    <a class="st-quiz-btn" href="skintype.php" style="display:inline-block; text-decoration:none; text-align:center;">
      Retake quiz
    </a>
  </div>

</body>
</html>
