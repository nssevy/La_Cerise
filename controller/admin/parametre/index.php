<?php
require_once dirname(__DIR__, 3) . '/vendor/autoload.php';
require_once dirname(__DIR__, 3) . '/config/db.php';
require_once dirname(__DIR__, 3) . '/config/twig.php';
require_once dirname(__DIR__, 3) . '/lib/auth.php';

requireLogin();

// Récupérer le profil de l'utilisateur connecté
$stmt = $pdo->prepare('SELECT id, nom, email FROM users WHERE id = ?');
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

$auteurs = $pdo->query('SELECT id, nom, bio, email FROM auteurs ORDER BY nom')->fetchAll();

$pages_legales = $pdo->query('SELECT type, titre FROM pages_legales ORDER BY id')->fetchAll();


echo $twig->render('admin/parametre/index.html.twig', [
    'user' => $user,
    'auteurs' => $auteurs,
    'pages_legales' => $pages_legales,
    'base' => $_ENV['BASE_URL'] ?? '',
    'section' => 'parametre',
    'user_nom' => $_SESSION['user_nom'],
]);