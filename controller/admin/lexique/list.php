<?php
require_once dirname(__DIR__, 3) . '/config/bootstrap.php';

requireLogin();

$categorie_id = $_GET['categorie'] ?? null;
$categories = $pdo->query('SELECT * FROM categories ORDER BY nom ASC')->fetchAll();

if ($categorie_id) {
    $stmt = $pdo->prepare('
        SELECT l.*, c.nom AS categorie_nom
        FROM lexique l
        LEFT JOIN categories c ON l.categorie_id = c.id
        WHERE l.categorie_id = ?
        ORDER BY l.terme ASC
    ');
    $stmt->execute([$categorie_id]);
} else {
    $stmt = $pdo->query('
        SELECT l.*, c.nom AS categorie_nom
        FROM lexique l
        LEFT JOIN categories c ON l.categorie_id = c.id
        ORDER BY l.terme ASC
    ');
}

$termes = $stmt->fetchAll();

echo $twig->render('admin/lexique/list.html.twig', [
    'termes' => $termes,
    'categories' => $categories,
    'categorie_id' => $categorie_id,
    'section' => 'lexique',
    ...get_flash(),
]);