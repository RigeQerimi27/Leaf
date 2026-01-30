<?php
declare(strict_types=1);

session_start();

$page = 'bath-relax';

$sections = [
    'on-sale' => 'On Sale',
    'bath-soaks' => 'Bath Soaks',
    'bath-foams' => 'Bath Foams',
    'aromatherapy-mists' => 'Aromatherapy Mists',
];

require_once __DIR__ . '/shop-template.php';
