<?php
require_once dirname(__DIR__, 3) . '/config/bootstrap.php';

requireLogin();

$id = $_POST['id'] ?? null;

if ($id) {
    $stmt = $pdo->prepare('DELETE FROM articles WHERE id = ?');
    $stmt->execute([$id]);
    flash_error('Article supprimé.');
}

redirect('/admin/article/list');