<?php
require_once dirname(__DIR__, 3) . '/config/bootstrap.php';

requireLogin();

$stmt = $pdo->prepare('SELECT id, nom, email FROM users WHERE id = ?');
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

$auteurs = $pdo->query('SELECT id, nom, bio, email FROM auteurs ORDER BY nom')->fetchAll();
$pages_legales = $pdo->query('SELECT type, titre FROM pages_legales ORDER BY id')->fetchAll();

echo $twig->render('admin/parametre/index.html.twig', [
    'user' => $user,
    'auteurs' => $auteurs,
    'pages_legales' => $pages_legales,
    'section' => 'parametre',
    ...get_flash(),
]);