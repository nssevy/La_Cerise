<?php
require_once dirname(__DIR__, 3) . '/config/bootstrap.php';
require_once dirname(__DIR__, 3) . '/src/utils/upload.php';
require_once dirname(__DIR__, 3) . '/src/repositories/ArticleRepository.php';
require_once dirname(__DIR__, 3) . '/src/services/BrevoService.php';

requireLogin();

$articleRepo = new ArticleRepository($pdo);
$errors = [];
$id = $_GET['id'] ?? null;

if (!$id)
    redirect('/admin/article/list');

$article = $articleRepo->findById((int) $id);
if (!$article)
    redirect('/admin/article/list');

$rubriques = $articleRepo->findRubriques();
$auteurs = $articleRepo->findAuteurs();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_verify();

    $ancienStatut = $article['statut'];

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

    $slug = $titre !== $article['titre'] ? slugify($titre) : $article['slug'];
    $image_principale = $article['image_principale'];

    if (!empty($_FILES['image_principale']['name'])) {
        $uploadDir = dirname(__DIR__, 3) . '/assets/images/';
        $result = handleImageUpload($_FILES['image_principale'], $errors, $uploadDir);
        if ($result !== null) {
            $image_principale = $result;
        }
    }

    if (empty($errors)) {
        $articleRepo->update((int) $id, [
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

        if ($statut === 'publie' && $ancienStatut !== 'publie') {
            (new BrevoService())->envoyerNewsletter($titre, $chapeau, $slug, $image_principale ?? '');
        }

        flash_success('Article mis à jour.');
        redirect('/admin/article/list');
    }

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
]);