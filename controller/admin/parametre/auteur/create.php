<?php
require_once dirname(__DIR__, 4) . '/config/bootstrap.php';

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

        flash_success('Auteur ajouté.');
        redirect('/admin/parametre');
    }

    $auteur = ['nom' => $nom, 'bio' => $bio, 'email' => $email];
}

echo $twig->render('admin/parametre/auteur_create.html.twig', [
    'auteur' => $auteur,
    'errors' => $errors,
    'section' => 'parametre',
]);