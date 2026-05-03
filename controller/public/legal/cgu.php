<?php
require_once dirname(__DIR__, 3) . '/config/db.php';
require_once dirname(__DIR__, 3) . '/config/twig.php';

$stmt = $pdo->prepare("SELECT titre, contenu, updated_at FROM pages_legales WHERE type = 'cgu'");
$stmt->execute();
$page = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$page) {
    http_response_code(404);
    exit('Page introuvable.');
}

echo $twig->render('public/legal/page_legale.html.twig', [
    'page' => $page,
    'title' => $page['titre'],
]);