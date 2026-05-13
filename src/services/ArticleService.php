<?php

class ArticleService
{
    /** Calcule le temps de lecture estimé d'un contenu HTML (200 mots/min, minimum 1 minute) */
    public function calculateReadingTime(string $contenu): int
    {
        $mots = str_word_count(strip_tags($contenu));
        return max(1, round($mots / 200));
    }

    /** Génère la table des matières à partir des balises h2 et injecte les id dans le contenu */
    public function generateToc(array &$article): array
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

    /** Enrichit le contenu en wrappant les termes du lexique dans des balises abbr */
    public function enrichirContenuAvecLexique(string $contenu, array $termes): string
    {
        foreach ($termes as $terme) {
            $mot = trim(preg_replace('/\s*\(.*\)/', '', $terme['terme']));
            $motEscape = preg_quote($mot, '/');

            $definition = htmlspecialchars($terme['definition'], ENT_QUOTES, 'UTF-8');
            $remplacement = '<abbr class="lexique-terme" data-definition="' . $definition . '">${1}</abbr>';

            // On remplace uniquement dans les noeuds texte, pas dans les attributs
            $contenu = preg_replace(
                '/(?<![a-zA-ZÀ-ÿ"\-])(' . $motEscape . 's?)(?![a-zA-ZÀ-ÿ"\-])(?=[^>]*(<|$))/ui',
                $remplacement,
                $contenu,
                1
            );
        }

        return $contenu;
    }
}