<?php
require_once dirname(__DIR__, 4) . '/config/bootstrap.php';
require_once dirname(__DIR__, 4) . '/src/repositories/RubriqueRepository.php';

requireLogin();

$rubriqueRepo = new RubriqueRepository($pdo);
$errors = [];
$id = $_GET['id'] ?? null;

if (!$id)
    redirect('/admin/parametre');

$rubrique = $rubriqueRepo->findById((int) $id);
if (!$rubrique)
    redirect('/admin/parametre');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_verify();

    $nom = trim($_POST['nom'] ?? '');
    $description = trim($_POST['description'] ?? '');

    if ($nom === '')
        $errors[] = 'Le nom est obligatoire.';

    if (empty($errors)) {
        $rubriqueRepo->update((int) $id, $nom, $rubriqueRepo->generateUniqueSlug($nom, (int) $id), $description ?: null);
        flash_success('Thème mis à jour.');
        redirect('/admin/parametre');
    }

    $rubrique['nom'] = $nom;
    $rubrique['description'] = $description;
}

echo $twig->render('admin/parametre/rubrique_edit.html.twig', [
    'rubrique' => $rubrique,
    'errors' => $errors,
    'section' => 'parametre',
]);
