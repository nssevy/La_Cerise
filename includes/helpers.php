<?php

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