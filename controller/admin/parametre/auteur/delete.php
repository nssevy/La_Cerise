<?php
require_once dirname(__DIR__, 4) . '/config/bootstrap.php';

requireLogin();

$id = $_GET['id'] ?? null;

if (!$id)
    redirect('/admin/parametre');

$stmt = $pdo->prepare('SELECT id FROM auteurs WHERE id = ?');
$stmt->execute([$id]);
$auteur = $stmt->fetch();

if (!$auteur)
    redirect('/admin/parametre');

// Vérifier si l'auteur est lié à des articles
$stmt = $pdo->prepare('SELECT titre FROM articles WHERE auteur_id = ?');
$stmt->execute([$id]);
$articles = $stmt->fetchAll(PDO::FETCH_COLUMN);

if (!empty($articles)) {
    $total = count($articles);
    $affiches = array_slice($articles, 0, 3);
    $message = 'Impossible de supprimer cet auteur. Articles liés : ' . implode(', ', $affiches);

    if ($total > 3) {
        $message .= ' et ' . ($total - 3) . ' autre' . ($total - 3 > 1 ? 's' : '') . '.';
    } else {
        $message .= '.';
    }

    flash_error($message);
    redirect('/admin/parametre');
}

$stmt = $pdo->prepare('DELETE FROM auteurs WHERE id = ?');
$stmt->execute([$id]);

flash_error('Auteur supprimé.');
redirect('/admin/parametre');