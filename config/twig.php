<?php
require_once dirname(__DIR__) . '/vendor/autoload.php';
require_once dirname(__DIR__) . '/includes/helpers.php';

$loader = new \Twig\Loader\FilesystemLoader(dirname(__DIR__) . '/templates');

$twig = new \Twig\Environment($loader, [
    'cache' => $_ENV['APP_ENV'] === 'production'
        ? dirname(__DIR__) . '/cache/twig'
        : false,
    'debug' => $_ENV['APP_ENV'] !== 'production',
]);

$twig->addGlobal('base', $_ENV['BASE_URL'] ?? '');

// La fonction helper
$twig->addFunction(new \Twig\TwigFunction('formatDateFr', function ($date) {
    return formatDateFr($date);
}));