<?php
require_once dirname(__DIR__, 3) . '/config/bootstrap.php';

requireLogin();

$errors = [];
$id = $_GET['id'] ?? null;

if (!$id)
    redirect('/admin/article/list');

$stmt = $pdo->prepare('SELECT * FROM articles WHERE id = ?');
$stmt->execute([$id]);
$article = $stmt->fetch();

if (!$article)
    redirect('/admin/article/list');

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

    if ($titre === '')
        $errors[] = 'Le titre est obligatoire.';
    if ($contenu === '')
        $errors[] = 'Le contenu est obligatoire.';

    $slug = $titre !== $article['titre'] ? slugify($titre) : $article['slug'];

    $image_principale = $article['image_principale'];

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

            if (!is_dir($uploadDir))
                mkdir($uploadDir, 0755, true);

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