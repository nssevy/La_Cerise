<?php
require_once dirname(__DIR__, 3) . '/config/bootstrap.php';

requireLogin();

$stmt = $pdo->query('
    SELECT l.*, r.nom AS rubrique_nom
    FROM lexique l
    LEFT JOIN rubriques r ON l.rubrique_id = r.id
    ORDER BY l.terme ASC
');
$termes = $stmt->fetchAll();

echo $twig->render('admin/lexique/list.html.twig', [
    'termes' => $termes,
]);
