<?php
require_once dirname(__DIR__, 2) . '/config/bootstrap.php';
require_once dirname(__DIR__, 2) . '/src/repositories/LexiqueRepository.php';

$lexiqueRepo = new LexiqueRepository($pdo);
$listTermes = $lexiqueRepo->findAllPublic();

$termesParLettre = [];
foreach ($listTermes as $terme) {
    $lettre = mb_strtoupper(mb_substr($terme['terme'], 0, 1));
    $termesParLettre[$lettre][] = $terme;
}
ksort($termesParLettre);

echo $twig->render('public/lexique.html.twig', [
    'termesParLettre' => $termesParLettre,
    'base' => $_ENV['BASE_URL'] ?? '',
]);