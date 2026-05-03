<?php
function slugify(string $texte): string
{
    $texte = preg_replace('/[\x{2018}\x{2019}\x{201A}\x{201B}\x{2032}\x{2035}\']/u', '-', $texte);
    $texte = mb_strtolower(trim($texte));
    $texte = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $texte);
    $texte = preg_replace('/[^a-z0-9]+/', '-', $texte);
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

function redirect(string $path): void
{
    $base = $_ENV['BASE_URL'] ?? '';
    header('Location: ' . $base . $path);
    exit;
}