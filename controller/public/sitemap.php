<?php
require_once dirname(__DIR__, 2) . '/config/bootstrap.php';
require_once dirname(__DIR__, 2) . '/src/repositories/ArticleRepository.php';

$articleRepo = new ArticleRepository($pdo);
$articles = $articleRepo->findAllPublished();

$siteUrl = rtrim($_ENV['APP_URL'] ?? 'https://lacerise.blog', '/');

header('Content-Type: application/xml; charset=utf-8');

echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <url>
        <loc><?= $siteUrl ?>/</loc>
        <changefreq>daily</changefreq>
        <priority>1.0</priority>
    </url>
    <url>
        <loc><?= $siteUrl ?>/allArticles</loc>
        <changefreq>daily</changefreq>
        <priority>0.8</priority>
    </url>
    <url>
        <loc><?= $siteUrl ?>/lexique</loc>
        <changefreq>weekly</changefreq>
        <priority>0.5</priority>
    </url>
    <?php foreach ($articles as $article): ?>
    <url>
        <loc><?= $siteUrl ?>/article/<?= htmlspecialchars($article['slug']) ?></loc>
        <?php if (!empty($article['date_publication'])): ?>
        <lastmod><?= date('Y-m-d', strtotime($article['date_publication'])) ?></lastmod>
        <?php endif; ?>
        <changefreq>monthly</changefreq>
        <priority>0.7</priority>
    </url>
    <?php endforeach; ?>
</urlset>
