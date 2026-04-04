<?php
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/config/twig.php';

$slug = $_GET['slug'] ?? null;

if (!$slug) {
    header('Location: ' . ($_ENV['BASE_URL'] ?? '') . '/');
    exit;
}

// Récupération de l'article
$stmt = $pdo->prepare('
    SELECT articles.*, auteurs.nom AS auteur_nom, rubriques.nom AS rubrique_nom
    FROM articles
    LEFT JOIN auteurs ON articles.auteur_id = auteurs.id
    LEFT JOIN rubriques ON articles.rubrique_id = rubriques.id
    WHERE articles.slug = :slug
    AND articles.statut = "publie"
');
$stmt->execute([':slug' => $slug]);
$article = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$article) {
    header('Location: ' . ($_ENV['BASE_URL'] ?? '') . '/public/');
    exit;
}

// Calcul du temps de lecture (200 mots/min)
$mots = str_word_count(strip_tags($article['contenu']));
$lecture = max(1, round($mots / 200));

// 3 articles suggérés hors article courant
$stmtSuggeres = $pdo->prepare('
    SELECT articles.*, auteurs.nom AS auteur_nom, rubriques.nom AS rubrique_nom
    FROM articles
    LEFT JOIN auteurs ON articles.auteur_id = auteurs.id
    LEFT JOIN rubriques ON articles.rubrique_id = rubriques.id
    WHERE articles.statut = "publie"
    AND articles.id != :id
    ORDER BY articles.date_publication DESC
    LIMIT 3
');
$stmtSuggeres->execute([':id' => $article['id']]);
$suggeres = $stmtSuggeres->fetchAll(PDO::FETCH_ASSOC);

echo $twig->render('article.html.twig', [
    'article' => $article,
    'lecture' => $lecture,
    'suggeres' => $suggeres,
]);