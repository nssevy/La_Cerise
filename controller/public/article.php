<?php
require_once dirname(__DIR__, 2) . '/config/bootstrap.php';
require_once dirname(__DIR__, 2) . '/src/services/ArticleService.php';
require_once dirname(__DIR__, 2) . '/src/repositories/ArticleRepository.php';
require_once dirname(__DIR__, 2) . '/src/repositories/LexiqueRepository.php';

$articleRepo = new ArticleRepository($pdo);
$lexiqueRepo = new LexiqueRepository($pdo);
$articleService = new ArticleService();

$slug = $_GET['slug'] ?? null;
if (!$slug)
    redirect('/');

$article = $articleRepo->findBySlug($slug);
if (!$article)
    redirect('/');

$toc = $articleService->generateToc($article);
$termes = $lexiqueRepo->findAllPublic();
$article['contenu'] = $articleService->enrichirContenuAvecLexique($article['contenu'], $termes);
$lecture = formatLecture($articleService->calculateReadingTime($article['contenu']));
$suggeres = $articleRepo->findSuggested($article['id']);

echo $twig->render('public/article.html.twig', [
    'article' => $article,
    'lecture' => $lecture,
    'suggeres' => $suggeres,
    'toc' => $toc,
    'base' => $_ENV['BASE_URL'] ?? '',
]);