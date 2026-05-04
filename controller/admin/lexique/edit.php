<?php
require_once dirname(__DIR__, 3) . '/config/bootstrap.php';

requireLogin();

$errors = [];
$id = $_GET['id'] ?? null;

if (!$id)
    redirect('/admin/lexique/list');

$stmt = $pdo->prepare('
    SELECT l.*, c.nom AS categorie_nom
    FROM lexique l
    LEFT JOIN categories c ON l.categorie_id = c.id
    WHERE l.id = ?
');
$stmt->execute([$id]);
$terme = $stmt->fetch();

if (!$terme)
    redirect('/admin/lexique/list');

$categories = $pdo->query('SELECT * FROM categories ORDER BY nom ASC')->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $termeVal = trim($_POST['terme'] ?? '');
    $definition = trim($_POST['definition'] ?? '');
    $categorie_id = $_POST['categorie_id'] !== '' ? (int) $_POST['categorie_id'] : null;

    if ($termeVal === '')
        $errors[] = 'Le terme est obligatoire.';
    if ($definition === '')
        $errors[] = 'La définition est obligatoire.';

    if (empty($errors)) {
        $stmt = $pdo->prepare('UPDATE lexique SET terme = ?, definition = ?, categorie_id = ? WHERE id = ?');
        $stmt->execute([$termeVal, $definition, $categorie_id, $id]);

        flash_success('Terme mis à jour.');
        redirect('/admin/lexique/list');
    }

    $terme = array_merge($terme, [
        'terme' => $termeVal,
        'definition' => $definition,
        'categorie_id' => $categorie_id,
    ]);
}

echo $twig->render('admin/lexique/edit.html.twig', [
    'terme' => $terme,
    'categories' => $categories,
    'errors' => $errors,
    'section' => 'lexique',
    ...get_flash(),
]);