<?php
require_once dirname(__DIR__, 4) . '/config/bootstrap.php';
require_once dirname(__DIR__, 4) . '/src/repositories/AuteurRepository.php';

requireLogin();

$auteurRepo = new AuteurRepository($pdo);
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
        $auteurRepo->insert($nom, $bio ?: null, $email ?: null);
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