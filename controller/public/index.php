<?php
require_once dirname(__DIR__, 2) . '/config/bootstrap.php';
require_once dirname(__DIR__, 2) . '/src/services/ArticleService.php';

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

$lectureHero = $hero ? formatLecture(calculateReadingTime($hero['contenu'])) : null;

$stmtCards = $pdo->prepare("
    SELECT a.*, r.nom AS rubrique
    FROM articles a
    LEFT JOIN rubriques r ON a.rubrique_id = r.id
    WHERE a.statut = 'publie' AND a.id != :id
    ORDER BY a.date_publication DESC
    LIMIT 2
");
$stmtCards->execute([':id' => $hero['id'] ?? 0]);
$cards = $stmtCards->fetchAll();

$lexique = $pdo->query("
    SELECT l.terme, c.nom AS categorie
    FROM lexique l
    LEFT JOIN categories c ON l.categorie_id = c.id
    ORDER BY l.terme ASC
")->fetchAll();

$stmtAVenir = $pdo->query("
    SELECT a.*, r.nom AS rubrique, au.nom AS auteur
    FROM articles a
    LEFT JOIN rubriques r  ON a.rubrique_id = r.id
    LEFT JOIN auteurs   au ON a.auteur_id   = au.id
    WHERE a.statut = 'a_venir'
    ORDER BY a.date_creation DESC
    LIMIT 1
");
$articleAVenir = $stmtAVenir->fetch();

$mediaNewsletter = $pdo->query("
    SELECT fichier, alt FROM medias WHERE contexte = 'newsletter' LIMIT 1
")->fetch();

$newsletterMessage = $_SESSION['newsletter'] ?? null;
unset($_SESSION['newsletter']);

echo $twig->render('public/index.html.twig', [
    'hero' => $hero,
    'cards' => $cards,
    'lexique' => $lexique,
    'lectureHero' => $lectureHero,
    'articleAVenir' => $articleAVenir,
    'mediaNewsletter' => $mediaNewsletter,
    'newsletterMessage' => $newsletterMessage,
    'base' => $_ENV['BASE_URL'] ?? '',
]);