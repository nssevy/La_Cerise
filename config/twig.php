<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/utils/helpers.php';

$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/../templates');

$twig = new \Twig\Environment($loader, [
    'cache' => false,
    'debug' => true,
]);

$twig->addGlobal('base', $_ENV['BASE_URL'] ?? '');
$twig->addGlobal('user_nom', $_SESSION['user_nom'] ?? '');
$twig->addGlobal('csrf_token', csrf_generate());

$rubriquesStmt = $pdo->query('SELECT id, nom FROM rubriques ORDER BY nom');
$twig->addGlobal('rubriquesNav', $rubriquesStmt->fetchAll());

$twig->addFunction(new \Twig\TwigFunction('formatDateFr', function ($date) {
    return formatDateFr($date);
}));