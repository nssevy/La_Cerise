<?php
require_once dirname(__DIR__, 3) . '/vendor/autoload.php';
require_once dirname(__DIR__, 3) . '/config/db.php';
require_once dirname(__DIR__, 3) . '/config/twig.php';
require_once dirname(__DIR__, 3) . '/lib/auth.php';

requireLogin();

$errors = [];
$success = false;

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

    // Génération du slug
    $slug = slugify($titre);

    if (empty($errors)) {
        $stmt = $pdo->prepare('
            INSERT INTO articles (titre, slug, chapeau, contenu, image_principale, credit_photo, statut, date_publication, rubrique_id, auteur_id)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
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
        ]);

        $base = $_ENV['BASE_URL'] ?? '';
        header('Location: ' . $base . '/admin/article/list');
        exit;
    }
}

echo $twig->render('admin/article_create.html.twig', [
    'errors' => $errors,
    'rubriques' => $rubriques,
    'auteurs' => $auteurs,
    'user_nom' => $_SESSION['user_nom'],
    'base' => $_ENV['BASE_URL'] ?? ''

]);