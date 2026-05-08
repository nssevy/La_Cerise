<?php
require_once dirname(__DIR__, 3) . '/config/bootstrap.php';
require_once dirname(__DIR__, 3) . '/src/repositories/ParametreRepository.php';
require_once dirname(__DIR__, 3) . '/src/repositories/AuteurRepository.php';

requireLogin();

$parametreRepo = new ParametreRepository($pdo);
$auteurRepo = new AuteurRepository($pdo);

$user = $parametreRepo->findUser($_SESSION['user_id']);
$auteurs = $auteurRepo->findAll();
$pages_legales = $parametreRepo->findPagesLegales();

echo $twig->render('admin/parametre/index.html.twig', [
    'user' => $user,
    'auteurs' => $auteurs,
    'pages_legales' => $pages_legales,
    'section' => 'parametre',
    ...get_flash(),
]);