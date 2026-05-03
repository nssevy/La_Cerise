<?php
require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

require_once __DIR__ . '/db.php';
require_once __DIR__ . '/twig.php';
require_once __DIR__ . '/../lib/auth.php';
require_once __DIR__ . '/../lib/flash.php';
require_once __DIR__ . '/../lib/helpers.php';