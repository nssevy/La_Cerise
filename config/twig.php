<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/helpers.php';

$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/../templates');

$twig = new \Twig\Environment($loader, [
    'cache' => false,
    'debug' => true,
]);

$twig->addGlobal('base', $_ENV['BASE_URL'] ?? '');
$twig->addGlobal('user_nom', $_SESSION['user_nom'] ?? '');

$twig->addFunction(new \Twig\TwigFunction('formatDateFr', function ($date) {
    return formatDateFr($date);
}));