<?php
require_once dirname(__DIR__, 3) . '/config/bootstrap.php';
require_once dirname(__DIR__, 3) . '/src/repositories/NewsletterRepository.php';

requireLogin();

$newsletterRepo = new NewsletterRepository($pdo);
$inscrits = $newsletterRepo->findAll();

echo $twig->render('admin/newsletter/list.html.twig', [
    'inscrits' => $inscrits,
    'section' => 'newsletter',
    ...get_flash(),
]);