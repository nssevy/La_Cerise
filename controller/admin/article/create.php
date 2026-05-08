<?php
require_once dirname(__DIR__, 3) . '/config/bootstrap.php';
require_once dirname(__DIR__, 3) . '/src/upload.php';
require_once dirname(__DIR__, 3) . '/src/repositories/ArticleRepository.php';

requireLogin();

$articleRepo = new ArticleRepository($pdo);
$errors = [];
$rubriques = $articleRepo->findRubriques();
$auteurs = $articleRepo->findAuteurs();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = trim($_POST['titre'] ?? '');
    $chapeau = trim($_POST['chapeau'] ?? '');
    $contenu = $_POST['contenu'] ?? '';
    $rubrique_id = $_POST['rubrique_id'] ?? null;
    $auteur_id = $_POST['auteur_id'] ?? null;
    $credit_photo = trim($_POST['credit_photo'] ?? '');
    $statut = $_POST['statut'] ?? 'brouillon';
    $date_publication = $_POST['date_publication'] ?? null;

    if ($titre === '')
        $errors[] = 'Le titre est obligatoire.';
    if ($contenu === '')
        $errors[] = 'Le contenu est obligatoire.';

    $slug = slugify($titre);
    $image_principale = null;

    if (!empty($_FILES['image_principale']['name'])) {
        $uploadDir = dirname(__DIR__, 3) . '/assets/images/';
        $image_principale = handleImageUpload($_FILES['image_principale'], $errors, $uploadDir);
    }

    if (empty($errors)) {
        $articleRepo->insert([
            'titre' => $titre,
            'slug' => $slug,
            'chapeau' => $chapeau ?: null,
            'contenu' => $contenu,
            'image_principale' => $image_principale ?: null,
            'credit_photo' => $credit_photo ?: null,
            'statut' => $statut,
            'date_publication' => $date_publication ?: null,
            'rubrique_id' => $rubrique_id ?: null,
            'auteur_id' => $auteur_id ?: null,
        ]);

        flash_success('Article créé avec succès.');
        redirect('/admin/article/list');
    }
}

echo $twig->render('admin/article_create.html.twig', [
    'errors' => $errors,
    'rubriques' => $rubriques,
    'auteurs' => $auteurs,
]);