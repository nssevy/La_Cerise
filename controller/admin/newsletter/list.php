<?php
require_once dirname(__DIR__, 3) . '/config/bootstrap.php';

requireLogin();

$inscrits = $pdo->query('SELECT * FROM newsletter ORDER BY date_inscription DESC')->fetchAll();

echo $twig->render('admin/newsletter/list.html.twig', [
    'inscrits' => $inscrits,
    'section' => 'newsletter',
    ...get_flash(),
]);