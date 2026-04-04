<?php
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/config/twig.php';

$termes = $pdo->query("
    SELECT terme, categorie, definition
    FROM lexique
    ORDER BY terme ASC
")->fetchAll(PDO::FETCH_ASSOC);

// Grouper les termes par première lettre
$termesParLettre = [];
foreach ($termes as $terme) {
    $lettre = mb_strtoupper(mb_substr($terme['terme'], 0, 1));
    $termesParLettre[$lettre][] = $terme;
}
ksort($termesParLettre);

echo $twig->render('lexique.html.twig', [
    'termesParLettre' => $termesParLettre,
]);