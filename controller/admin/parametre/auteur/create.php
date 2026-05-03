<?php
require_once dirname(__DIR__, 4) . '/vendor/autoload.php';
require_once dirname(__DIR__, 4) . '/config/db.php';
require_once dirname(__DIR__, 4) . '/config/twig.php';
require_once dirname(__DIR__, 4) . '/lib/auth.php';

requireLogin();

$errors = [];
$auteur = ['nom' => '', 'bio' => '', 'email' => ''];

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
        $stmt = $pdo->prepare('INSERT INTO auteurs (nom, bio, email) VALUES (?, ?, ?)');
        $stmt->execute([$nom, $bio ?: null, $email ?: null]);

        header('Location: ' . ($_ENV['BASE_URL'] ?? '') . '/admin/parametre');
        exit;
    }

    $auteur = ['nom' => $nom, 'bio' => $bio, 'email' => $email];
}

echo $twig->render('admin/parametre/auteur_create.html.twig', [
    'auteur' => $auteur,
    'errors' => $errors,
    'base' => $_ENV['BASE_URL'] ?? '',
    'section' => 'parametre',
    'user_nom' => $_SESSION['user_nom'],
]);