<?php
require_once dirname(__DIR__, 3) . '/config/bootstrap.php';
require_once dirname(__DIR__, 3) . '/src/repositories/ArticleRepository.php';

requireLogin();
csrf_verify();

$id     = (int) ($_POST['id'] ?? 0);
$statut = $_POST['statut'] ?? '';

$statutsValides = ['brouillon', 'publie', 'a_venir', 'archive'];

if (!$id || !in_array($statut, $statutsValides))
    redirect('/admin/article/list');

$articleRepo = new ArticleRepository($pdo);
$article = $articleRepo->findById($id);

if (!$article)
    redirect('/admin/article/list');

$articleRepo->updateStatut($id, $statut);

redirect('/admin/article/list');
