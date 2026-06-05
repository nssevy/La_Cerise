<?php
require_once dirname(__DIR__, 2) . '/config/bootstrap.php';
require_once dirname(__DIR__, 2) . '/src/services/ArticleService.php';
require_once dirname(__DIR__, 2) . '/src/repositories/ArticleRepository.php';
require_once dirname(__DIR__, 2) . '/src/repositories/LexiqueRepository.php';
require_once dirname(__DIR__, 2) . '/src/repositories/MediaRepository.php';

$articleRepo = new ArticleRepository($pdo);
$lexiqueRepo = new LexiqueRepository($pdo);
$mediaRepo = new MediaRepository($pdo);
$articleService = new ArticleService();

$hero = $articleRepo->findHero();
$lectureHero = $hero ? formatLecture($articleService->calculateReadingTime($hero['contenu'])) : null;
$cards = $articleRepo->findCards($hero['id'] ?? 0);
$cards = $articleRepo->findCards($hero['id'] ?? 0);
$gridArticles = $hero ? array_merge([$hero], $cards) : $cards;
$articleAVenir = $articleRepo->findUpcoming();
$lexique = $lexiqueRepo->findAllPublic();
$mediaNewsletter = $mediaRepo->findNewsletterMedia();

// IDs déjà utilisés dans le carousel
$excludeIds = array_column($cards, 'id');
if ($hero)
    $excludeIds[] = $hero['id'];

$gridArticles = $articleRepo->findExcluding($excludeIds);


$newsletterMessage = $_SESSION['newsletter'] ?? null;
unset($_SESSION['newsletter']);

echo $twig->render('public/index.html.twig', [
    'hero' => $hero,
    'cards' => $cards,
    'gridArticles' => $gridArticles,
    'lexique' => $lexique,
    'lectureHero' => $lectureHero,
    'articleAVenir' => $articleAVenir,
    'mediaNewsletter' => $mediaNewsletter,
    'newsletterMessage' => $newsletterMessage,
    'base' => $_ENV['BASE_URL'] ?? '',
]);