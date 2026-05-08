<?php
require_once dirname(__DIR__, 3) . '/config/bootstrap.php';
require_once dirname(__DIR__, 3) . '/src/repositories/ParametreRepository.php';
require_once dirname(__DIR__, 3) . '/src/repositories/AuteurRepository.php';

requireLogin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST')
    redirect('/admin/parametre');

csrf_verify();

$parametreRepo = new ParametreRepository($pdo);
$auteurRepo = new AuteurRepository($pdo);

$errors = [];
$nom = trim($_POST['nom'] ?? '');
$email = trim($_POST['email'] ?? '');
$mdp = $_POST['mot_de_passe'] ?? '';
$mdp2 = $_POST['mot_de_passe_confirm'] ?? '';

if ($nom === '')
    $errors[] = 'Le nom est obligatoire.';
if ($email === '')
    $errors[] = 'L\'email est obligatoire.';
if (!filter_var($email, FILTER_VALIDATE_EMAIL))
    $errors[] = 'L\'email n\'est pas valide.';

if ($mdp !== '') {
    if (strlen($mdp) < 8)
        $errors[] = 'Le mot de passe doit contenir au moins 8 caractères.';
    if ($mdp !== $mdp2)
        $errors[] = 'Les mots de passe ne correspondent pas.';
}

if (empty($errors)) {
    if ($mdp !== '') {
        $parametreRepo->updateUserWithPassword(
            $_SESSION['user_id'],
            $nom,
            $email,
            password_hash($mdp, PASSWORD_DEFAULT)
        );
    } else {
        $parametreRepo->updateUser($_SESSION['user_id'], $nom, $email);
    }

    $_SESSION['user_nom'] = $nom;
    $twig->addGlobal('user_nom', $nom);

    flash_success($mdp !== '' ? 'Mot de passe modifié.' : 'Profil mis à jour.');
    redirect('/admin/parametre');
}

$user = $parametreRepo->findUser($_SESSION['user_id']);
$auteurs = $auteurRepo->findAll();
$pages_legales = $parametreRepo->findPagesLegales();

echo $twig->render('admin/parametre/index.html.twig', [
    'user' => $user,
    'auteurs' => $auteurs,
    'pages_legales' => $pages_legales,
    'errors' => $errors,
    'section' => 'parametre',
]);