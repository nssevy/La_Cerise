<?php
require_once dirname(__DIR__) . '/vendor/autoload.php';
require_once dirname(__DIR__) . '/config/db.php';
require_once dirname(__DIR__) . '/config/twig.php';
require_once dirname(__DIR__) . '/includes/auth.php';

requireLogin();

$errors = [];
$id = $_GET['id'] ?? null;

if (!$id) {
    header('Location: /La_Cerise/admin/dashboard.php');
    exit;
}

// Récupérer l'article
$stmt = $pdo->prepare('SELECT * FROM articles WHERE id = ?');
$stmt->execute([$id]);
$article = $stmt->fetch();

if (!$article) {
    header('Location: /La_Cerise/admin/dashboard.php');
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
    $image_principale = trim($_POST['image_principale'] ?? '');
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
        $slug = strtolower(trim($titre));
        $slug = iconv('UTF-8', 'ASCII//TRANSLIT', $slug);
        $slug = preg_replace('/[^a-z0-9]+/', '-', $slug);
        $slug = trim($slug, '-');
    } else {
        $slug = $article['slug'];
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
        header('Location: ' . $base . '/admin/dashboard.php');
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
    'errors' => $errors,
    'rubriques' => $rubriques,
    'auteurs' => $auteurs,
    'base' => $_ENV['BASE_URL'] ?? ''

]);