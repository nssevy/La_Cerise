<?php
require_once dirname(__DIR__, 4) . '/config/bootstrap.php';
require_once dirname(__DIR__, 4) . '/src/repositories/RubriqueRepository.php';

requireLogin();

$rubriqueRepo = new RubriqueRepository($pdo);
$errors = [];
$rubrique = ['nom' => '', 'description' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_verify();

    $nom = trim($_POST['nom'] ?? '');
    $description = trim($_POST['description'] ?? '');

    if ($nom === '')
        $errors[] = 'Le nom est obligatoire.';

    if (empty($errors)) {
        $rubriqueRepo->insert($nom, $rubriqueRepo->generateUniqueSlug($nom), $description ?: null);
        flash_success('Thème ajouté.');
        redirect('/admin/parametre');
    }

    $rubrique = ['nom' => $nom, 'description' => $description];
}

echo $twig->render('admin/parametre/rubrique_create.html.twig', [
    'rubrique' => $rubrique,
    'errors' => $errors,
    'section' => 'parametre',
]);
