<?php
require_once dirname(__DIR__, 3) . '/config/bootstrap.php';
require_once dirname(__DIR__, 3) . '/src/repositories/LexiqueRepository.php';

requireLogin();

$lexiqueRepo = new LexiqueRepository($pdo);
$errors = [];
$categories = $lexiqueRepo->findCategories();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_verify();

    $terme = trim($_POST['terme'] ?? '');
    $definition = trim($_POST['definition'] ?? '');
    $categorieId = $_POST['categorie_id'] !== '' ? (int) $_POST['categorie_id'] : null;

    if ($terme === '')
        $errors[] = 'Le terme est obligatoire.';
    if ($definition === '')
        $errors[] = 'La définition est obligatoire.';

    if (empty($errors)) {
        $lexiqueRepo->insert($terme, $definition, $categorieId);
        flash_success('Terme ajouté au lexique.');
        redirect('/admin/lexique/list');
    }
}

echo $twig->render('admin/lexique/create.html.twig', [
    'errors' => $errors,
    'categories' => $categories,
    'section' => 'lexique',
    ...get_flash(),
]);