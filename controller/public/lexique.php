<?php
require_once dirname(__DIR__, 2) . '/config/bootstrap.php';

$listTermes = $pdo->query("
    SELECT l.terme, l.definition, c.nom AS categorie
    FROM lexique l
    LEFT JOIN categories c ON l.categorie_id = c.id
    ORDER BY l.terme ASC
")->fetchAll(PDO::FETCH_ASSOC);

// Grouper les termes par première lettre
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