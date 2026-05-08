<?php
require_once dirname(__DIR__, 3) . '/config/bootstrap.php';

requireLogin();

$id = $_POST['id'] ?? null;

if ($id) {
    $stmt = $pdo->prepare('DELETE FROM newsletter WHERE id = ?');
    $stmt->execute([$id]);
    flash_error('Inscrit supprimé.');
}

redirect('/admin/newsletter/list');