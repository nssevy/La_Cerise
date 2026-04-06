<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../includes/helpers.php';

$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/../templates');

$twig = new \Twig\Environment($loader, [
    'cache' => $_ENV['APP_ENV'] === 'production'
        ? __DIR__ . '/../cache/twig'
        : false,
    'debug' => $_ENV['APP_ENV'] !== 'production',
]);

$twig->addGlobal('base', $_ENV['BASE_URL'] ?? '');

$twig->addFunction(new \Twig\TwigFunction('formatDateFr', function ($date) {
    return formatDateFr($date);
}));