<?php
require_once dirname(__DIR__, 2) . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__, 2));
$dotenv->load();


require_once dirname(__DIR__, 2) . '/includes/auth.php';

logout();