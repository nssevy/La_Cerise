<?php
require_once dirname(__DIR__, 3) . '/vendor/autoload.php';
require_once dirname(__DIR__, 3) . '/config/db.php';
require_once dirname(__DIR__, 3) . '/config/twig.php';
require_once dirname(__DIR__, 3) . '/lib/auth.php';

requireLogin();

$errors = [];
$id = $_GET['id'] ?? null;

if (!$id) {
    header('Location: ' . ($_ENV['BASE_URL'] ?? '') . '/admin/article/list');
    exit;
}

// Récupérer l'article
$stmt = $pdo->prepare('SELECT * FROM articles WHERE id = ?');
$stmt->execute([$id]);
$article = $stmt->fetch();

if (!$article) {
    header('Location: ' . ($_ENV['BASE_URL'] ?? '') . '/admin/article/list');
    exit;
}

// Récupérer les rubriques et auteurs pour les selects
$rubriques = $pdo->query('SELECT id, nom FROM rubriques ORDER BY nom')->fetchAll();
$auteurs = $pdo->query('SELECT id, nom FROM auteurs ORDER BY nom')->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = trim($_POST['titre'] ?? '');
    $chapeau = trim($_POST['chapeau'] ?? '');
    $contenu = $_POST['contenu'] ?? '';
    $rubrique_id = $_POST['rubrique_id'] ?? null;
    $auteur_id = $_POST['auteur_id'] ?? null;
    $credit_photo = trim($_POST['credit_photo'] ?? '');
    $statut = $_POST['statut'] ?? 'brouillon';
    $date_publication = $_POST['date_publication'] ?? null;

    // Validation
    if ($titre === '')
        $errors[] = 'Le titre est obligatoire.';
    if ($contenu === '')
        $errors[] = 'Le contenu est obligatoire.';

    // Régénération du slug uniquement si le titre a changé
    if ($titre !== $article['titre']) {
        $slug = slugify($titre);
    } else {
        $slug = $article['slug'];
    }

    // Gestion de l'image uploadée
    $image_principale = $article['image_principale']; // valeur existante par défaut

    if (!empty($_FILES['image_principale']['name'])) {
        $file = $_FILES['image_principale'];
        $allowed = ['image/jpeg', 'image/png'];

        if (!in_array($file['type'], $allowed)) {
            $errors[] = 'Format d\'image non supporté (JPEG ou PNG uniquement).';
        } elseif ($file['size'] > 5 * 1024 * 1024) {
            $errors[] = 'L\'image ne doit pas dépasser 5 Mo.';
        } else {
            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            $filename = uniqid('img_') . '.' . $ext;
            $uploadDir = dirname(__DIR__, 3) . '/assets/images/';

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            if (move_uploaded_file($file['tmp_name'], $uploadDir . $filename)) {
                $image_principale = $filename;
            } else {
                $errors[] = 'Erreur lors de l\'upload de l\'image.';
            }
        }
    }

    if (empty($errors)) {
        $stmt = $pdo->prepare('
            UPDATE articles
            SET titre = ?, slug = ?, chapeau = ?, contenu = ?, image_principale = ?,
                credit_photo = ?, statut = ?, date_publication = ?, rubrique_id = ?, auteur_id = ?
            WHERE id = ?
        ');

        $stmt->execute([
            $titre,
            $slug,
            $chapeau ?: null,
            $contenu,
            $image_principale ?: null,
            $credit_photo ?: null,
            $statut,
            $date_publication ?: null,
            $rubrique_id ?: null,
            $auteur_id ?: null,
            $id,
        ]);

        $base = $_ENV['BASE_URL'] ?? '';
        header('Location: ' . $base . '/admin/article/list');
        exit;
    }

    // En cas d'erreur, on met à jour $article avec les valeurs soumises
    $article = array_merge($article, [
        'titre' => $titre,
        'chapeau' => $chapeau,
        'contenu' => $contenu,
        'rubrique_id' => $rubrique_id,
        'auteur_id' => $auteur_id,
        'image_principale' => $image_principale,
        'credit_photo' => $credit_photo,
        'statut' => $statut,
        'date_publication' => $date_publication,
    ]);
}

echo $twig->render('admin/article_edit.html.twig', [
    'article' => $article,
    'user_nom' => $_SESSION['user_nom'],
    'errors' => $errors,
    'rubriques' => $rubriques,
    'auteurs' => $auteurs,
    'base' => $_ENV['BASE_URL'] ?? ''
]);