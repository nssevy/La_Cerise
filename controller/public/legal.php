<?php
require_once dirname(__DIR__, 2) . '/config/bootstrap.php';

$types_valides = ['cgu', 'confidentialite', 'mentions_legales'];
$type = $_GET['type'] ?? null;

if (!$type || !in_array($type, $types_valides)) {
    redirect('/');
}

$stmt = $pdo->prepare("SELECT titre, contenu, updated_at FROM pages_legales WHERE type = ?");
$stmt->execute([$type]);
$page = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$page) {
    http_response_code(404);
    exit('Page introuvable.');
}

echo $twig->render('public/legal/page_legale.html.twig', [
    'page' => $page,
    'title' => $page['titre'],
    'base' => $_ENV['BASE_URL'] ?? '',
]);