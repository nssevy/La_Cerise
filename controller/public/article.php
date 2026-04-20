<?php
require_once dirname(__DIR__, 2) . '/config/db.php';
require_once dirname(__DIR__, 2) . '/config/twig.php';

$slug = $_GET['slug'] ?? null;

if (!$slug) {
    header('Location: ' . ($_ENV['BASE_URL'] ?? '') . '/');
    exit;
}

// Récupération de l'article
$stmt = $pdo->prepare('
    SELECT articles.*, auteurs.nom AS auteur_nom, rubriques.nom AS rubrique_nom
    FROM articles
    LEFT JOIN auteurs ON articles.auteur_id = auteurs.id
    LEFT JOIN rubriques ON articles.rubrique_id = rubriques.id
    WHERE articles.slug = :slug
    AND articles.statut = "publie"
');
$stmt->execute([':slug' => $slug]);
$article = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$article) {
    header('Location: ' . ($_ENV['BASE_URL'] ?? '') . '/');
    exit;
}

// Calcul du temps de lecture (200 mots/min)
$mots = str_word_count(strip_tags($article['contenu']));
$lecture = max(1, round($mots / 200));

// 3 articles suggérés hors article courant
$stmtSuggeres = $pdo->prepare('
    SELECT articles.*, auteurs.nom AS auteur_nom, rubriques.nom AS rubrique_nom
    FROM articles
    LEFT JOIN auteurs ON articles.auteur_id = auteurs.id
    LEFT JOIN rubriques ON articles.rubrique_id = rubriques.id
    WHERE articles.statut = "publie"
    AND articles.id != :id
    ORDER BY articles.date_publication DESC
    LIMIT 3
');
$stmtSuggeres->execute([':id' => $article['id']]);
$suggeres = $stmtSuggeres->fetchAll(PDO::FETCH_ASSOC);

// Génération automatique de la table des matières (TOC)
$toc = [];
if ($article['contenu']) {
    $dom = new DOMDocument();
    libxml_use_internal_errors(true);
    $dom->loadHTML('<?xml encoding="utf-8"?><div id="wrapper">' . $article['contenu'] . '</div>');
    libxml_clear_errors();

    $h2s = $dom->getElementsByTagName('h2');
    $h2sArray = iterator_to_array($h2s);

    foreach ($h2sArray as $h2) {
        if (!($h2 instanceof DOMElement))
            continue;

        $texte = $h2->textContent;
        $ancre = strtolower(preg_replace('/[^a-z0-9]+/i', '-', $texte));
        $toc[] = ['texte' => $texte, 'ancre' => $ancre];
        $h2->setAttribute('id', $ancre);
    }

    // Récupérer uniquement le contenu du wrapper
    $wrapper = $dom->getElementById('wrapper');
    $contenuAvecAncres = '';
    foreach ($wrapper->childNodes as $node) {
        $contenuAvecAncres .= $dom->saveHTML($node);
    }
    $article['contenu'] = $contenuAvecAncres;
}

echo $twig->render('public/article.html.twig', [
    'article' => $article,
    'lecture' => $lecture,
    'suggeres' => $suggeres,
    'toc' => $toc,
    'base' => $_ENV['BASE_URL'] ?? '',

]);
