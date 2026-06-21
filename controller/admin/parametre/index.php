<?php
require_once dirname(__DIR__, 3) . '/config/bootstrap.php';
require_once dirname(__DIR__, 3) . '/src/repositories/ParametreRepository.php';
require_once dirname(__DIR__, 3) . '/src/repositories/AuteurRepository.php';
require_once dirname(__DIR__, 3) . '/src/repositories/RubriqueRepository.php';

requireLogin();

$parametreRepo = new ParametreRepository($pdo);
$auteurRepo = new AuteurRepository($pdo);
$rubriqueRepo = new RubriqueRepository($pdo);

$user = $parametreRepo->findUser($_SESSION['user_id']);
$auteurs = $auteurRepo->findAll();
$rubriques = $rubriqueRepo->findAll();
$pages_legales = $parametreRepo->findPagesLegales();

echo $twig->render('admin/parametre/index.html.twig', [
    'user' => $user,
    'auteurs' => $auteurs,
    'rubriques' => $rubriques,
    'pages_legales' => $pages_legales,
    'section' => 'parametre',
    ...get_flash(),
]);