<?php
require_once dirname(__DIR__, 3) . '/config/bootstrap.php';

requireLogin();

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $terme = trim($_POST['terme'] ?? '');
    $definition = trim($_POST['definition'] ?? '');
    $categorie = trim($_POST['categorie'] ?? '');

    if ($terme === '')
        $errors[] = 'Le terme est obligatoire.';
    if ($definition === '')
        $errors[] = 'La définition est obligatoire.';

    if (empty($errors)) {
        $stmt = $pdo->prepare('INSERT INTO lexique (terme, definition, categorie) VALUES (?, ?, ?)');
        $stmt->execute([$terme, $definition, $categorie ?: null]);

        flash_success('Terme ajouté au lexique.');
        redirect('/admin/lexique/list');
    }
}

echo $twig->render('admin/lexique_create.html.twig', [
    'errors' => $errors,
]);