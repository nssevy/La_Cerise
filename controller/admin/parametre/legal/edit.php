<?php
require_once dirname(__DIR__, 4) . '/vendor/autoload.php';
require_once dirname(__DIR__, 4) . '/config/db.php';
require_once dirname(__DIR__, 4) . '/config/twig.php';
require_once dirname(__DIR__, 4) . '/lib/auth.php';

requireLogin();

$types_valides = ['confidentialite', 'mentions_legales', 'cgu'];
$type = $_GET['type'] ?? '';

if (!in_array($type, $types_valides)) {
    header('Location: ' . ($_ENV['BASE_URL'] ?? '') . '/admin/parametre');
    exit;
}

$errors = [];
$success = false;

$stmt = $pdo->prepare('SELECT * FROM pages_legales WHERE type = ?');
$stmt->execute([$type]);
$page = $stmt->fetch();

if (!$page) {
    header('Location: ' . ($_ENV['BASE_URL'] ?? '') . '/admin/parametre');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = trim($_POST['titre'] ?? '');
    $contenu = $_POST['contenu'] ?? '';

    if ($titre === '')
        $errors[] = 'Le titre est obligatoire.';
    if ($contenu === '')
        $errors[] = 'Le contenu est obligatoire.';

    if (empty($errors)) {
        $stmt = $pdo->prepare('UPDATE pages_legales SET titre = ?, contenu = ? WHERE type = ?');
        $stmt->execute([$titre, $contenu, $type]);
        $success = true;

        $stmt = $pdo->prepare('SELECT * FROM pages_legales WHERE type = ?');
        $stmt->execute([$type]);
        $page = $stmt->fetch();
    } else {
        $page['titre'] = $titre;
        $page['contenu'] = $contenu;
    }
}

echo $twig->render('admin/parametre/legal_edit.html.twig', [
    'page' => $page,
    'errors' => $errors,
    'success' => $success,
    'base' => $_ENV['BASE_URL'] ?? '',
    'section' => 'parametre',
    'user_nom' => $_SESSION['user_nom'],
]);