<?php
require_once dirname(__DIR__, 3) . '/vendor/autoload.php';
require_once dirname(__DIR__, 3) . '/config/db.php';
require_once dirname(__DIR__, 3) . '/config/twig.php';
require_once dirname(__DIR__, 3) . '/includes/auth.php';

requireLogin();

$stmt = $pdo->query('
    SELECT a.id, a.titre, a.statut, a.date_publication, a.date_creation,
           r.nom AS rubrique
    FROM articles a
    LEFT JOIN rubriques r ON a.rubrique_id = r.id
    ORDER BY a.date_creation DESC
');

$articles = $stmt->fetchAll();

echo $twig->render('admin/dashboard.html.twig', [
    'articles' => $articles,
    'user_nom' => $_SESSION['user_nom'],
    'base' => $_ENV['BASE_URL'] ?? ''
]);