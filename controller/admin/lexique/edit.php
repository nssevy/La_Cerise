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

$rubriques = $pdo->query('SELECT * FROM rubriques ORDER BY nom ASC')->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $termeVal = trim($_POST['terme'] ?? '');
    $definition = trim($_POST['definition'] ?? '');
    $rubrique_id = $_POST['rubrique_id'] !== '' ? (int) $_POST['rubrique_id'] : null;

    if ($termeVal === '')
        $errors[] = 'Le terme est obligatoire.';
    if ($definition === '')
        $errors[] = 'La définition est obligatoire.';

    if (empty($errors)) {
        $stmt = $pdo->prepare('UPDATE lexique SET terme = ?, definition = ?, rubrique_id = ? WHERE id = ?');
        $stmt->execute([$termeVal, $definition, $rubrique_id, $id]);

        flash_success('Terme mis à jour.');
        redirect('/admin/lexique/list');
    }

    $terme = array_merge($terme, [
        'terme'      => $termeVal,
        'definition' => $definition,
        'rubrique_id' => $rubrique_id,
    ]);
}

echo $twig->render('admin/lexique/edit.html.twig', [
    'terme'     => $terme,
    'rubriques' => $rubriques,
    'errors'    => $errors,
]);
