<?php

/**
 * Gère l'upload d'une image depuis $_FILES.
 *
 * @param array  $file      Entrée $_FILES['nom_du_champ']
 * @param array  &$errors   Tableau d'erreurs passé par référence
 * @param string $uploadDir Chemin absolu du dossier de destination
 *
 * @return string|null Nom du fichier uploadé, ou null en cas d'échec
 */
function handleImageUpload(array $file, array &$errors, string $uploadDir): ?string
{
    $allowed = ['image/jpeg', 'image/png'];

    if (!in_array($file['type'], $allowed)) {
        $errors[] = 'Format d\'image non supporté (JPEG ou PNG uniquement).';
        return null;
    }

    if ($file['size'] > 5 * 1024 * 1024) {
        $errors[] = 'L\'image ne doit pas dépasser 5 Mo.';
        return null;
    }

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = uniqid('img_') . '.' . $ext;

    if (!move_uploaded_file($file['tmp_name'], $uploadDir . $filename)) {
        $errors[] = 'Erreur lors de l\'upload de l\'image.';
        return null;
    }

    return $filename;
}