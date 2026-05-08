<?php
require_once dirname(__DIR__, 2) . '/config/bootstrap.php';
require_once dirname(__DIR__, 2) . '/src/services/ArticleService.php';
require_once dirname(__DIR__, 2) . '/src/repositories/ArticleRepository.php';

$articleRepo = new ArticleRepository($pdo);
$articleService = new ArticleService();

$hero = $articleRepo->findHero();
$lectureHero = $hero ? formatLecture($articleService->calculateReadingTime($hero['contenu'])) : null;
$cards = $articleRepo->findCards($hero['id'] ?? 0);
$articleAVenir = $articleRepo->findUpcoming();

$lexique = $pdo->query("
    SELECT l.terme, c.nom AS categorie
    FROM lexique l
    LEFT JOIN categories c ON l.categorie_id = c.id
    ORDER BY l.terme ASC
")->fetchAll();

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