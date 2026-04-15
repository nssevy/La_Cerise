<?php
require_once dirname(__DIR__) . '/config/db.php';
require_once dirname(__DIR__) . '/config/twig.php';

$slug = $_GET['slug'] ?? null;

if (!$slug) {
    header('Location: ' . ($_ENV['BASE_URL'] ?? '') . '/controller/');
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
    header('Location: ' . ($_ENV['BASE_URL'] ?? '') . '/controller/');
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
// Parcourt le contenu HTML de l'article via DOMDocument,
// extrait chaque balise <h2>, crée un slug (ancre) à partir de son texte,
// injecte un attribut id="ancre" sur chaque <h2> dans le contenu,
// et construit le tableau $toc utilisé par le template pour afficher les liens de navigation.
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

echo $twig->render('article.html.twig', [
    'article' => $article,
    'lecture' => $lecture,
    'suggeres' => $suggeres,
    'toc' => $toc,
    'base' => $_ENV['BASE_URL'] ?? '',

]);