<?php
require_once dirname(__DIR__) . '/vendor/autoload.php';
require_once dirname(__DIR__) . '/config/db.php';
require_once dirname(__DIR__) . '/includes/auth.php';

requireLogin();

$id = $_POST['id'] ?? null;

if ($id) {
    $stmt = $pdo->prepare('DELETE FROM articles WHERE id = ?');
    $stmt->execute([$id]);
}

$base = $_ENV['BASE_URL'] ?? '';
header('Location: ' . $base . '/admin/dashboard.php');
exit;