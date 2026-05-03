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

$stmt = $pdo->prepare('DELETE FROM auteurs WHERE id = ?');
$stmt->execute([$id]);

flash_error('Auteur supprimé.');
redirect('/admin/parametre');