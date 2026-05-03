<?php
require_once dirname(__DIR__, 4) . '/vendor/autoload.php';
require_once dirname(__DIR__, 4) . '/config/db.php';
require_once dirname(__DIR__, 4) . '/lib/auth.php';

requireLogin();

$id = $_GET['id'] ?? null;

if (!$id) {
    header('Location: ' . ($_ENV['BASE_URL'] ?? '') . '/admin/parametre');
    exit;
}

// Vérifier que l'auteur existe
$stmt = $pdo->prepare('SELECT id FROM auteurs WHERE id = ?');
$stmt->execute([$id]);
$auteur = $stmt->fetch();

if (!$auteur) {
    header('Location: ' . ($_ENV['BASE_URL'] ?? '') . '/admin/parametre');
    exit;
}

$stmt = $pdo->prepare('DELETE FROM auteurs WHERE id = ?');
$stmt->execute([$id]);

header('Location: ' . ($_ENV['BASE_URL'] ?? '') . '/admin/parametre');
exit;