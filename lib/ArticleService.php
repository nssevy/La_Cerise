<?php

/**
 * Calcule le temps de lecture estimé d'un contenu HTML.
 * Base : 200 mots par minute, minimum 1 minute.
 *
 * @param string $contenu Contenu HTML de l'article
 * @return int Nombre de minutes
 */
function calculateReadingTime(string $contenu): int
{
    $mots = str_word_count(strip_tags($contenu));
    return max(1, round($mots / 200));
}

/**
 * Génère la table des matières à partir des balises <h2> du contenu,
 * et injecte les attributs id correspondants dans le HTML.
 *
 * @param array &$article Article passé par référence — 'contenu' est modifié
 * @return array Liste des entrées TOC ['texte' => ..., 'ancre' => ...]
 */
function generateToc(array &$article): array
{
    $toc = [];

    if (empty($article['contenu'])) {
        return $toc;
    }

    $dom = new DOMDocument();
    libxml_use_internal_errors(true);
    $dom->loadHTML('<?xml encoding="utf-8"?><div id="wrapper">' . $article['contenu'] . '</div>');
    libxml_clear_errors();

    $h2sArray = iterator_to_array($dom->getElementsByTagName('h2'));

    foreach ($h2sArray as $h2) {
        if (!($h2 instanceof DOMElement))
            continue;

        $texte = $h2->textContent;
        $ancre = strtolower(preg_replace('/[^a-z0-9]+/i', '-', $texte));
        $toc[] = ['texte' => $texte, 'ancre' => $ancre];
        $h2->setAttribute('id', $ancre);
    }

    $wrapper = $dom->getElementById('wrapper');
    $contenuAvecAncres = '';
    foreach ($wrapper->childNodes as $node) {
        $contenuAvecAncres .= $dom->saveHTML($node);
    }

    $article['contenu'] = $contenuAvecAncres;

    return $toc;
}