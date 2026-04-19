<?php
require_once dirname(__DIR__, 3) . '/vendor/autoload.php';
require_once dirname(__DIR__, 3) . '/config/db.php';
require_once dirname(__DIR__, 3) . '/includes/auth.php';

requireLogin();

$id = $_POST['id'] ?? null;

if ($id) {
    $stmt = $pdo->prepare('DELETE FROM lexique WHERE id = ?');
    $stmt->execute([$id]);
}

$base = $_ENV['BASE_URL'] ?? '';
header('Location: ' . $base . '/admin/lexique/list');
exit;