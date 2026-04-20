<?php
require_once dirname(__DIR__, 3) . '/vendor/autoload.php';
require_once dirname(__DIR__, 3) . '/config/db.php';
require_once dirname(__DIR__, 3) . '/config/twig.php';
require_once dirname(__DIR__, 3) . '/lib/auth.php';

requireLogin();

$stmt = $pdo->query('
    SELECT a.id, a.titre, a.statut, a.date_publication, a.date_creation,
           a.image_principale,
           r.nom AS rubrique
    FROM articles a
    LEFT JOIN rubriques r ON a.rubrique_id = r.id
    ORDER BY a.date_creation DESC
');

$articles = $stmt->fetchAll();

$stmtStats = $pdo->query('
    SELECT statut, COUNT(*) as total
    FROM articles
    GROUP BY statut
');

$stats = $stmtStats->fetchAll(PDO::FETCH_KEY_PAIR);

echo $twig->render('admin/dashboard.html.twig', [
    'articles' => $articles,
    'stats' => $stats,
    'user_nom' => $_SESSION['user_nom'],
    'base' => $_ENV['BASE_URL'] ?? '',
    'section' => 'Vos articles'
]);