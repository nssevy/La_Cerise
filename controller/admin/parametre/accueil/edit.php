<?php
require_once dirname(__DIR__, 4) . '/config/bootstrap.php';
require_once dirname(__DIR__, 4) . '/src/repositories/BlocAccueilRepository.php';

requireLogin();

$blocRepo = new BlocAccueilRepository($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_verify();

    foreach ($blocRepo->findAll() as $bloc) {
        $valeur = trim($_POST['bloc_' . $bloc['id']] ?? '');
        $blocRepo->update((int) $bloc['id'], $valeur ?: null);
    }

    flash_success('Section « Pourquoi La Cerise » mise à jour.');
    redirect('/admin/parametre');
}

$blocs = $blocRepo->findAll();

echo $twig->render('admin/parametre/accueil_edit.html.twig', [
    'blocs' => $blocs,
    'section' => 'parametre',
]);
