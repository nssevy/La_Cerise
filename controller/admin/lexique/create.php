<?php
require_once dirname(__DIR__, 3) . '/vendor/autoload.php';
require_once dirname(__DIR__, 3) . '/config/db.php';
require_once dirname(__DIR__, 3) . '/config/twig.php';
require_once dirname(__DIR__, 3) . '/includes/auth.php';

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

        $base = $_ENV['BASE_URL'] ?? '';
        header('Location: ' . $base . '/admin/lexique/list');
        exit;
    }
}

echo $twig->render('admin/lexique_create.html.twig', [
    'errors' => $errors,
    'base' => $_ENV['BASE_URL'] ?? ''
]);