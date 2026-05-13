<?php
http_response_code(404);
require_once __DIR__ . '/config/bootstrap.php';

echo $twig->render('public/page-introuvable.html.twig', [
    'base' => $_ENV['BASE_URL'] ?? '',
]);