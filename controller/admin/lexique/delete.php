<?php
require_once dirname(__DIR__, 3) . '/config/bootstrap.php';
require_once dirname(__DIR__, 3) . '/src/repositories/LexiqueRepository.php';

requireLogin();
csrf_verify();

$lexiqueRepo = new LexiqueRepository($pdo);
$id = $_POST['id'] ?? null;

if ($id) {
    $lexiqueRepo->delete((int) $id);
    flash_error('Terme supprimé.');
}

redirect('/admin/lexique/list');