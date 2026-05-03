<?php
require_once dirname(__DIR__, 4) . '/config/bootstrap.php';

requireLogin();

$types_valides = ['confidentialite', 'mentions_legales', 'cgu'];
$type = $_GET['type'] ?? '';

if (!in_array($type, $types_valides))
    redirect('/admin/parametre');

$errors = [];

$stmt = $pdo->prepare('SELECT * FROM pages_legales WHERE type = ?');
$stmt->execute([$type]);
$page = $stmt->fetch();

if (!$page)
    redirect('/admin/parametre');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = trim($_POST['titre'] ?? '');
    $contenu = $_POST['contenu'] ?? '';

    if ($titre === '')
        $errors[] = 'Le titre est obligatoire.';
    if ($contenu === '')
        $errors[] = 'Le contenu est obligatoire.';

    if (empty($errors)) {
        $stmt = $pdo->prepare('UPDATE pages_legales SET titre = ?, contenu = ? WHERE type = ?');
        $stmt->execute([$titre, $contenu, $type]);

        flash_success('Page mise à jour.');
        redirect('/admin/parametre/legal/edit?type=' . $type);
    }

    $page['titre'] = $titre;
    $page['contenu'] = $contenu;
}

echo $twig->render('admin/parametre/legal_edit.html.twig', [
    'page' => $page,
    'errors' => $errors,
    'section' => 'parametre',
    ...get_flash(),
]);