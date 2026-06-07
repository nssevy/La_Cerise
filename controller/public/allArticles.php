<?php
require_once dirname(__DIR__, 2) . '/config/bootstrap.php';
require_once dirname(__DIR__, 2) . '/src/repositories/ArticleRepository.php';

$articleRepo = new ArticleRepository($pdo);

$articles = $articleRepo->findAllPublished();
$rubriques = $articleRepo->findRubriques();
$stats = $articleRepo->getStats();


echo $twig->render('public/allArticles.html.twig', [
    'articles' => $articles,
    'rubriques' => $rubriques,
    'stats' => $stats,
    'base' => $_ENV['BASE_URL'] ?? '',
]);
