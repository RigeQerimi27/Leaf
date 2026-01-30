<?php
declare(strict_types=1);

session_start();

$page = 'bodycare';

$sections = [
    'on-sale' => 'On Sale',
    'body-lotions' => 'Body Lotions',
    'body-scrubs' => 'Body scrubs',
    'body-oils' => 'Body Oils',
];

require_once __DIR__ . '/shop-template.php';
