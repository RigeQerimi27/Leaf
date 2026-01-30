<?php
declare(strict_types=1);

session_start();

$page = 'skincare';

$sections = [
    'on-sale' => 'On Sale',
    'cleansers' => 'Cleansers',
    'serums' => 'Serums',
    'moisturizers' => 'Moisturizers',
];

require_once __DIR__ . '/shop-template.php';
