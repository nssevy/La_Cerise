<?php
require_once dirname(__DIR__, 3) . '/config/bootstrap.php';
require_once dirname(__DIR__, 3) . '/src/repositories/LexiqueRepository.php';

requireLogin();

$lexiqueRepo = new LexiqueRepository($pdo);
$categorieId = isset($_GET['categorie']) && $_GET['categorie'] !== '' ? (int) $_GET['categorie'] : null;

$termes = $lexiqueRepo->findAllAdmin($categorieId);
$categories = $lexiqueRepo->findCategories();

echo $twig->render('admin/lexique/list.html.twig', [
    'termes' => $termes,
    'categories' => $categories,
    'categorie_id' => $categorieId,
    'section' => 'lexique',
    ...get_flash(),
]);