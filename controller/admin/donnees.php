<?php
require_once dirname(__DIR__, 2) . '/config/bootstrap.php';
require_once dirname(__DIR__, 2) . '/src/services/GoogleAnalyticsService.php';

requireLogin();

$gaService = new GoogleAnalyticsService();
$apercu = $gaService->isConfigured() ? $gaService->getApercu() : null;

echo $twig->render('admin/donnees/index.html.twig', [
    'apercu' => $apercu,
    'ga_configure' => $gaService->isConfigured(),
    'section' => 'donnees',
    ...get_flash(),
]);
