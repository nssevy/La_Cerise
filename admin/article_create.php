<?php
require_once dirname(__DIR__) . '/vendor/autoload.php';
require_once dirname(__DIR__) . '/config/db.php';
require_once dirname(__DIR__) . '/config/twig.php';
require_once dirname(__DIR__) . '/includes/auth.php';

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
    $slug = strtolower(trim($titre));
    $slug = iconv('UTF-8', 'ASCII//TRANSLIT', $slug);
    $slug = preg_replace('/[^a-z0-9]+/', '-', $slug);
    $slug = trim($slug, '-');

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
        header('Location: ' . $base . '/admin/dashboard.php');
        exit;
    }
}

echo $twig->render('admin/article_create.html.twig', [
    'errors' => $errors,
    'rubriques' => $rubriques,
    'auteurs' => $auteurs,
    'base' => $_ENV['BASE_URL'] ?? ''

]);