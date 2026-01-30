<?php
declare(strict_types=1);

session_start();

$page = 'bestsellers';

$sections = [
    'on-sale' => 'On Sale',
    'bestsellers-skincare' => 'Skincare',
    'bestsellers-bodycare' => 'Body care',
    'bestsellers-bath-relax' => 'Bath & Relax',
];

require_once __DIR__ . '/shop-template.php';
