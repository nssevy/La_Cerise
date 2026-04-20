<?php
require_once dirname(__DIR__, 3) . '/vendor/autoload.php';
require_once dirname(__DIR__, 3) . '/config/db.php';
require_once dirname(__DIR__, 3) . '/config/twig.php';
require_once dirname(__DIR__, 3) . '/lib/auth.php';

requireLogin();

$errors = [];
$id = $_GET['id'] ?? null;

if (!$id) {
    header('Location: ' . ($_ENV['BASE_URL'] ?? '') . '/admin/lexique/list');
    exit;
}

$stmt = $pdo->prepare('SELECT * FROM lexique WHERE id = ?');
$stmt->execute([$id]);
$terme = $stmt->fetch();

if (!$terme) {
    header('Location: ' . ($_ENV['BASE_URL'] ?? '') . '/admin/lexique/list');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $termeVal = trim($_POST['terme'] ?? '');
    $definition = trim($_POST['definition'] ?? '');
    $categorie = trim($_POST['categorie'] ?? '');

    if ($termeVal === '')
        $errors[] = 'Le terme est obligatoire.';
    if ($definition === '')
        $errors[] = 'La définition est obligatoire.';

    if (empty($errors)) {
        $stmt = $pdo->prepare('UPDATE lexique SET terme = ?, definition = ?, categorie = ? WHERE id = ?');
        $stmt->execute([$termeVal, $definition, $categorie ?: null, $id]);

        $base = $_ENV['BASE_URL'] ?? '';
        header('Location: ' . $base . '/admin/lexique/list');
        exit;
    }

    $terme = array_merge($terme, [
        'terme' => $termeVal,
        'definition' => $definition,
        'categorie' => $categorie,
    ]);
}

echo $twig->render('admin/lexique_edit.html.twig', [
    'terme' => $terme,
    'errors' => $errors,
    'base' => $_ENV['BASE_URL'] ?? ''
]);