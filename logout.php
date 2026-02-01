<?php
declare(strict_types=1);

require_once __DIR__ . '/Database.php';
require_once __DIR__ . '/UserRepository.php';
require_once __DIR__ . '/AuthService.php';

$db = new Database();
$userRepo = new UserRepository($db);
$auth = new AuthService($userRepo);


$auth->logout();
header("Location: signin.php");
exit;
