<?php
require_once dirname(__DIR__, 4) . '/config/bootstrap.php';
require_once dirname(__DIR__, 4) . '/src/repositories/RubriqueRepository.php';

requireLogin();
csrf_verify();

$rubriqueRepo = new RubriqueRepository($pdo);
$id = $_POST['id'] ?? null;

if (!$id)
    redirect('/admin/parametre');

if (!$rubriqueRepo->exists((int) $id))
    redirect('/admin/parametre');

$articles = $rubriqueRepo->findLinkedArticles((int) $id);

if (!empty($articles)) {
    $total = count($articles);
    $affiches = array_slice($articles, 0, 3);
    $message = 'Impossible de supprimer ce thème. Articles liés : ' . implode(', ', $affiches);

    if ($total > 3) {
        $message .= ' et ' . ($total - 3) . ' autre' . ($total - 3 > 1 ? 's' : '') . '.';
    } else {
        $message .= '.';
    }

    flash_error($message);
    redirect('/admin/parametre');
}

$rubriqueRepo->delete((int) $id);
flash_error('Thème supprimé.');
redirect('/admin/parametre');
