<?php
require_once dirname(__DIR__, 2) . '/vendor/autoload.php';
require_once dirname(__DIR__, 2) . '/config/db.php';
require_once dirname(__DIR__, 2) . '/includes/auth.php';

requireLogin();

$id = $_POST['id'] ?? null;

if ($id) {
    $stmt = $pdo->prepare('DELETE FROM articles WHERE id = ?');
    $stmt->execute([$id]);
}

$base = $_ENV['BASE_URL'] ?? '';
header('Location: ' . $base . '/controller/admin/dashboard.php');
exit;