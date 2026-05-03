<?php
require_once dirname(__DIR__, 4) . '/vendor/autoload.php';
require_once dirname(__DIR__, 4) . '/config/db.php';
require_once dirname(__DIR__, 4) . '/config/twig.php';
require_once dirname(__DIR__, 4) . '/lib/auth.php';

requireLogin();

$id = $_GET['id'] ?? null;

if (!$id) {
    header('Location: ' . ($_ENV['BASE_URL'] ?? '') . '/admin/parametre');
    exit;
}

$stmt = $pdo->prepare('SELECT * FROM auteurs WHERE id = ?');
$stmt->execute([$id]);
$auteur = $stmt->fetch();

if (!$auteur) {
    header('Location: ' . ($_ENV['BASE_URL'] ?? '') . '/admin/parametre');
    exit;
}

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom'] ?? '');
    $bio = trim($_POST['bio'] ?? '');
    $email = trim($_POST['email'] ?? '');

    if ($nom === '')
        $errors[] = 'Le nom est obligatoire.';
    if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'L\'email n\'est pas valide.';
    }

    if (empty($errors)) {
        $stmt = $pdo->prepare('UPDATE auteurs SET nom = ?, bio = ?, email = ? WHERE id = ?');
        $stmt->execute([$nom, $bio ?: null, $email ?: null, $id]);
        $success = true;

        $stmt = $pdo->prepare('SELECT * FROM auteurs WHERE id = ?');
        $stmt->execute([$id]);
        $auteur = $stmt->fetch();
    } else {
        $auteur['nom'] = $nom;
        $auteur['bio'] = $bio;
        $auteur['email'] = $email;
    }
}

echo $twig->render('admin/parametre/auteur_edit.html.twig', [
    'auteur' => $auteur,
    'errors' => $errors,
    'success' => $success,
    'base' => $_ENV['BASE_URL'] ?? '',
    'section' => 'parametre',
    'user_nom' => $_SESSION['user_nom'],
]);