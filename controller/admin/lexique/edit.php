<?php
require_once dirname(__DIR__, 3) . '/config/bootstrap.php';

requireLogin();

$errors = [];
$id = $_GET['id'] ?? null;

if (!$id)
    redirect('/admin/lexique/list');

$stmt = $pdo->prepare('SELECT * FROM lexique WHERE id = ?');
$stmt->execute([$id]);
$terme = $stmt->fetch();

if (!$terme)
    redirect('/admin/lexique/list');

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

        flash_success('Terme mis à jour.');
        redirect('/admin/lexique/list');
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
]);