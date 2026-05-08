<?php
require_once dirname(__DIR__, 3) . '/config/bootstrap.php';
require_once dirname(__DIR__, 3) . '/src/repositories/NewsletterRepository.php';

requireLogin();
csrf_verify();

$newsletterRepo = new NewsletterRepository($pdo);
$id = $_POST['id'] ?? null;

if ($id) {
    $newsletterRepo->delete((int) $id);
    flash_error('Inscrit supprimé.');
}

redirect('/admin/newsletter/list');