<?php
require_once dirname(__DIR__, 4) . '/config/bootstrap.php';
require_once dirname(__DIR__, 4) . '/src/repositories/AuteurRepository.php';

requireLogin();

$auteurRepo = new AuteurRepository($pdo);
$id = $_GET['id'] ?? null;

if (!$id)
    redirect('/admin/parametre');

if (!$auteurRepo->exists((int) $id))
    redirect('/admin/parametre');

$articles = $auteurRepo->findLinkedArticles((int) $id);

if (!empty($articles)) {
    $total = count($articles);
    $affiches = array_slice($articles, 0, 3);
    $message = 'Impossible de supprimer cet auteur. Articles liés : ' . implode(', ', $affiches);

    if ($total > 3) {
        $message .= ' et ' . ($total - 3) . ' autre' . ($total - 3 > 1 ? 's' : '') . '.';
    } else {
        $message .= '.';
    }

    flash_error($message);
    redirect('/admin/parametre');
}

$auteurRepo->delete((int) $id);
flash_error('Auteur supprimé.');
redirect('/admin/parametre');