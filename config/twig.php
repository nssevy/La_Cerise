<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/utils/helpers.php';

$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/../templates');

$twig = new \Twig\Environment($loader, [
    'cache' => false,
    'debug' => true,
]);

$twig->addGlobal('base', $_ENV['BASE_URL'] ?? '');
$twig->addGlobal('current_path', $_SERVER['REQUEST_URI'] ?? '');
$twig->addGlobal('user_nom', $_SESSION['user_nom'] ?? '');
$twig->addGlobal('csrf_token', csrf_generate());

require_once __DIR__ . '/../src/repositories/RubriqueRepository.php';

$rubriqueRepo = new RubriqueRepository($pdo);
$twig->addGlobal('rubriquesNav', $rubriqueRepo->findAll());

$infosStmt = $pdo->query('SELECT id, type, titre FROM pages_legales ORDER BY type');
$twig->addGlobal('infosNav', $infosStmt->fetchAll());

require_once __DIR__ . '/../src/repositories/BlocAccueilRepository.php';
$blocAccueilRepoGlobal = new BlocAccueilRepository($pdo);
$twig->addGlobal('blocsAccueil', $blocAccueilRepoGlobal->findAll());

$twig->addFunction(new \Twig\TwigFunction('formatDateFr', function ($date) {
    return formatDateFr($date);
}));