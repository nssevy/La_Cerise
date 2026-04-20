<?php

function slugify(string $texte): string
{
    // Remplacer les apostrophes et guillemets typographiques par un tiret
    $texte = preg_replace('/[\x{2018}\x{2019}\x{201A}\x{201B}\x{2032}\x{2035}\']/u', '-', $texte);

    // Passer en minuscules (UTF-8 safe)
    $texte = mb_strtolower(trim($texte));

    // Translitérer les caractères accentués en ASCII (//IGNORE ignore les caractères non convertibles)
    $texte = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $texte);

    // Remplacer tout ce qui n'est pas alphanumérique par un tiret
    $texte = preg_replace('/[^a-z0-9]+/', '-', $texte);

    // Supprimer les tirets en début et fin
    return trim($texte, '-');
}

function formatDateFr(?string $date): string
{
    if (!$date)
        return 'Date non renseignée';

    $mois = [
        1 => 'janvier',
        'février',
        'mars',
        'avril',
        'mai',
        'juin',
        'juillet',
        'août',
        'septembre',
        'octobre',
        'novembre',
        'décembre'
    ];

    $d = new DateTime($date);
    return $d->format('d') . ' ' . $mois[(int) $d->format('n')] . ' ' . $d->format('Y');
}
