<?php
require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/db.php';
require_once __DIR__ . '/twig.php';
require_once __DIR__ . '/../src/auth.php';
require_once __DIR__ . '/../src/flash.php';
require_once __DIR__ . '/../src/helpers.php';