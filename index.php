<?php
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/config/twig.php';

// Article hero (le plus récent)
$stmtHero = $pdo->query("
    SELECT a.*, r.nom AS rubrique, au.nom AS auteur
    FROM articles a
    LEFT JOIN rubriques r  ON a.rubrique_id = r.id
    LEFT JOIN auteurs   au ON a.auteur_id   = au.id
    WHERE a.statut = 'publie'
    ORDER BY a.date_publication DESC
    LIMIT 1
");
$hero = $stmtHero->fetch();

// Calcul du temps de lecture (200 mots/min)
$motsHero = $hero ? str_word_count(strip_tags($hero['contenu'])) : 0;
$lectureHero = max(1, round($motsHero / 200));

// 4 articles suivants pour les cards
$stmtCards = $pdo->prepare("
    SELECT a.*, r.nom AS rubrique
    FROM articles a
    LEFT JOIN rubriques r ON a.rubrique_id = r.id
    WHERE a.statut = 'publie' AND a.id != :id
    ORDER BY a.date_publication DESC
    LIMIT 4
");
$stmtCards->execute([':id' => $hero['id'] ?? 0]);
$cards = $stmtCards->fetchAll();

// Termes du lexique
$lexique = $pdo->query("SELECT terme, categorie FROM lexique ORDER BY terme ASC")->fetchAll();

echo $twig->render('index.html.twig', [
    'hero' => $hero,
    'cards' => $cards,
    'lexique' => $lexique,
    'lectureHero' => $lectureHero,
]);