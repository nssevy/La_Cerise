<?php
require_once dirname(__DIR__, 3) . '/config/bootstrap.php';

requireLogin();

$stmt = $pdo->query('SELECT * FROM lexique ORDER BY terme ASC');
$termes = $stmt->fetchAll();

echo $twig->render('admin/lexique_list.html.twig', [
    'termes' => $termes,
    'section' => 'lexique',
    ...get_flash(),
]);