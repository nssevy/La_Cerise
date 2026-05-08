<?php
require_once dirname(__DIR__, 3) . '/config/bootstrap.php';
require_once dirname(__DIR__, 3) . '/src/repositories/ArticleRepository.php';

requireLogin();

$articleRepo = new ArticleRepository($pdo);
$articles = $articleRepo->findAllAdmin();
$stats = $articleRepo->getStats();

echo $twig->render('admin/dashboard.html.twig', [
    'articles' => $articles,
    'stats' => $stats,
    'section' => 'articles',
    ...get_flash(),
]);