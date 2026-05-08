<?php
require_once dirname(__DIR__, 4) . '/config/bootstrap.php';
require_once dirname(__DIR__, 4) . '/src/repositories/AuteurRepository.php';

requireLogin();

$auteurRepo = new AuteurRepository($pdo);
$errors = [];
$id = $_GET['id'] ?? null;

if (!$id)
    redirect('/admin/parametre');

$auteur = $auteurRepo->findById((int) $id);
if (!$auteur)
    redirect('/admin/parametre');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_verify();

    $nom = trim($_POST['nom'] ?? '');
    $bio = trim($_POST['bio'] ?? '');
    $email = trim($_POST['email'] ?? '');

    if ($nom === '')
        $errors[] = 'Le nom est obligatoire.';
    if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'L\'email n\'est pas valide.';
    }

    if (empty($errors)) {
        $auteurRepo->update((int) $id, $nom, $bio ?: null, $email ?: null);
        flash_success('Auteur mis à jour.');
        redirect('/admin/parametre');
    }

    $auteur['nom'] = $nom;
    $auteur['bio'] = $bio;
    $auteur['email'] = $email;
}

echo $twig->render('admin/parametre/auteur_edit.html.twig', [
    'auteur' => $auteur,
    'errors' => $errors,
    'section' => 'parametre',
]);