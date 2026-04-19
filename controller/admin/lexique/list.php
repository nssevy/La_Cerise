<?php
require_once dirname(__DIR__, 3) . '/vendor/autoload.php';
require_once dirname(__DIR__, 3) . '/config/db.php';
require_once dirname(__DIR__, 3) . '/config/twig.php';
require_once dirname(__DIR__, 3) . '/includes/auth.php';

requireLogin();

$stmt = $pdo->query('SELECT * FROM lexique ORDER BY terme ASC');
$termes = $stmt->fetchAll();

echo $twig->render('admin/lexique_list.html.twig', [
    'termes' => $termes,
    'user_nom' => $_SESSION['user_nom'],
    'base' => $_ENV['BASE_URL'] ?? ''
]);