<?php
require_once dirname(__DIR__, 4) . '/config/bootstrap.php';

requireLogin();

$id = $_GET['id'] ?? null;

if (!$id)
    redirect('/admin/parametre');

$stmt = $pdo->prepare('SELECT * FROM auteurs WHERE id = ?');
$stmt->execute([$id]);
$auteur = $stmt->fetch();

if (!$auteur)
    redirect('/admin/parametre');

$errors = [];

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