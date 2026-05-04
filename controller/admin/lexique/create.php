<?php
require_once dirname(__DIR__, 3) . '/config/bootstrap.php';

requireLogin();

$errors = [];
$rubriques = $pdo->query('SELECT * FROM rubriques ORDER BY nom ASC')->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $terme = trim($_POST['terme'] ?? '');
    $definition = trim($_POST['definition'] ?? '');
    $rubrique_id = $_POST['rubrique_id'] !== '' ? (int) $_POST['rubrique_id'] : null;

    if ($terme === '')
        $errors[] = 'Le terme est obligatoire.';
    if ($definition === '')
        $errors[] = 'La définition est obligatoire.';

    if (empty($errors)) {
        $stmt = $pdo->prepare('INSERT INTO lexique (terme, definition, rubrique_id) VALUES (?, ?, ?)');
        $stmt->execute([$terme, $definition, $rubrique_id]);

        flash_success('Terme ajouté au lexique.');
        redirect('/admin/lexique/list');
    }
}

echo $twig->render('admin/lexique/create.html.twig', [
    'errors' => $errors,
    'rubriques' => $rubriques,
]);
