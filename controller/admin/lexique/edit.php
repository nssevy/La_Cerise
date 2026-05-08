<?php
require_once dirname(__DIR__, 3) . '/config/bootstrap.php';
require_once dirname(__DIR__, 3) . '/src/repositories/LexiqueRepository.php';

requireLogin();

$lexiqueRepo = new LexiqueRepository($pdo);
$errors = [];
$id = $_GET['id'] ?? null;

if (!$id)
    redirect('/admin/lexique/list');

$terme = $lexiqueRepo->findById((int) $id);
if (!$terme)
    redirect('/admin/lexique/list');

$categories = $lexiqueRepo->findCategories();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $termeVal = trim($_POST['terme'] ?? '');
    $definition = trim($_POST['definition'] ?? '');
    $categorieId = $_POST['categorie_id'] !== '' ? (int) $_POST['categorie_id'] : null;

    if ($termeVal === '')
        $errors[] = 'Le terme est obligatoire.';
    if ($definition === '')
        $errors[] = 'La définition est obligatoire.';

    if (empty($errors)) {
        $lexiqueRepo->update((int) $id, $termeVal, $definition, $categorieId);
        flash_success('Terme mis à jour.');
        redirect('/admin/lexique/list');
    }

    $terme = array_merge($terme, [
        'terme' => $termeVal,
        'definition' => $definition,
        'categorie_id' => $categorieId,
    ]);
}

echo $twig->render('admin/lexique/edit.html.twig', [
    'terme' => $terme,
    'categories' => $categories,
    'errors' => $errors,
    'section' => 'lexique',
    ...get_flash(),
]);