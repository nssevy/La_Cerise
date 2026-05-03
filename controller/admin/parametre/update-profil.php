<?php
require_once dirname(__DIR__, 3) . '/config/bootstrap.php';

requireLogin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST')
    redirect('/admin/parametre');

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
        $stmt = $pdo->prepare('UPDATE users SET nom = ?, email = ?, mot_de_passe = ? WHERE id = ?');
        $stmt->execute([$nom, $email, password_hash($mdp, PASSWORD_DEFAULT), $_SESSION['user_id']]);
    } else {
        $stmt = $pdo->prepare('UPDATE users SET nom = ?, email = ? WHERE id = ?');
        $stmt->execute([$nom, $email, $_SESSION['user_id']]);
    }

    $_SESSION['user_nom'] = $nom;
    $twig->addGlobal('user_nom', $nom);

    flash_success($mdp !== '' ? 'Mot de passe modifié.' : 'Profil mis à jour.');
    redirect('/admin/parametre');
}

$stmt = $pdo->prepare('SELECT id, nom, email FROM users WHERE id = ?');
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

$auteurs = $pdo->query('SELECT id, nom, bio, email FROM auteurs ORDER BY nom')->fetchAll();
$pages_legales = $pdo->query('SELECT type, titre FROM pages_legales ORDER BY id')->fetchAll();

echo $twig->render('admin/parametre/index.html.twig', [
    'user' => $user,
    'auteurs' => $auteurs,
    'pages_legales' => $pages_legales,
    'errors' => $errors,
    'section' => 'parametre',
]);