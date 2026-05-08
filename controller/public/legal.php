<?php
require_once dirname(__DIR__, 2) . '/config/bootstrap.php';
require_once dirname(__DIR__, 2) . '/src/repositories/ParametreRepository.php';

$parametreRepo = new ParametreRepository($pdo);
$types_valides = ['cgu', 'confidentialite', 'mentions_legales'];
$type = $_GET['type'] ?? null;

if (!$type || !in_array($type, $types_valides))
    redirect('/');

$page = $parametreRepo->findPageLegaleByType($type);

if (!$page) {
    http_response_code(404);
    exit('Page introuvable.');
}

echo $twig->render('public/legal/page_legale.html.twig', [
    'page' => $page,
    'title' => $page['titre'],
    'base' => $_ENV['BASE_URL'] ?? '',
]);