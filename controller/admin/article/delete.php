<?php
require_once dirname(__DIR__, 3) . '/config/bootstrap.php';
require_once dirname(__DIR__, 3) . '/src/repositories/ArticleRepository.php';

requireLogin();
csrf_verify();

$articleRepo = new ArticleRepository($pdo);
$id = $_POST['id'] ?? null;

if ($id) {
    $articleRepo->delete((int) $id);
    flash_error('Article supprimé.');
}

redirect('/admin/article/list');