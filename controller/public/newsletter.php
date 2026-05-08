<?php
require_once dirname(__DIR__, 2) . '/config/bootstrap.php';
require_once dirname(__DIR__, 2) . '/src/repositories/NewsletterRepository.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST['email'])) {
    redirect('/');
}

$email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['newsletter'] = ['type' => 'erreur', 'texte' => 'Adresse e-mail invalide.'];
    redirect('/');
}

$newsletterRepo = new NewsletterRepository($pdo);

try {
    $newsletterRepo->insert($email);
    $_SESSION['newsletter'] = ['type' => 'succes', 'texte' => 'Vous êtes bien inscrit à la newsletter.'];
} catch (PDOException $e) {
    $_SESSION['newsletter'] = $e->getCode() === '23000'
        ? ['type' => 'erreur', 'texte' => 'Cet email est déjà inscrit.']
        : ['type' => 'erreur', 'texte' => 'Une erreur est survenue, veuillez réessayer.'];
}

redirect('/');