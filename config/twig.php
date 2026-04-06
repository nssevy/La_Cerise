<?php
require_once dirname(__DIR__) . '/vendor/autoload.php';
require_once dirname(__DIR__) . '/includes/helpers.php';

$loader = new \Twig\Loader\FilesystemLoader(dirname(__DIR__) . '/templates');

$twig = new \Twig\Environment($loader, [
    'cache' => false,
    'debug' => true,
]);

$twig->addGlobal('base', $_ENV['BASE_URL'] ?? '');

// La fonction helper
$twig->addFunction(new \Twig\TwigFunction('formatDateFr', function ($date) {
    return formatDateFr($date);
}));