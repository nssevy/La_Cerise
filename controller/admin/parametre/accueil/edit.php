<?php
require_once dirname(__DIR__, 4) . '/config/bootstrap.php';
require_once dirname(__DIR__, 4) . '/src/repositories/BlocAccueilRepository.php';
require_once dirname(__DIR__, 4) . '/src/repositories/CitationRepository.php';

requireLogin();

$blocRepo = new BlocAccueilRepository($pdo);
$citationRepo = new CitationRepository($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_verify();

    foreach ($blocRepo->findAll() as $bloc) {
        $valeur = trim($_POST['bloc_' . $bloc['id']] ?? '');
        $blocRepo->update((int) $bloc['id'], $valeur ?: null);
    }

    $citation = $citationRepo->find();
    if ($citation) {
        $citationRepo->update((int) $citation['id'], trim($_POST['citation'] ?? '') ?: null);
    }

    flash_success('Page d\'accueil mise à jour.');
    redirect('/admin/parametre');
}

$blocs = $blocRepo->findAll();
$citation = $citationRepo->find();

echo $twig->render('admin/parametre/accueil_edit.html.twig', [
    'blocs' => $blocs,
    'citation' => $citation,
    'section' => 'parametre',
]);
