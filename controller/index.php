<?php
require_once dirname(__DIR__) . '/config/db.php';
require_once dirname(__DIR__) . '/config/twig.php';

// Article hero (le plus récent)
$stmtHero = $pdo->query("
    SELECT a.*, r.nom AS rubrique, au.nom AS auteur
    FROM articles a
    LEFT JOIN rubriques r  ON a.rubrique_id = r.id
    LEFT JOIN auteurs   au ON a.auteur_id   = au.id
    WHERE a.statut = 'publie'
    ORDER BY a.date_publication DESC
    LIMIT 1
");
$hero = $stmtHero->fetch();

// Calcul du temps de lecture (200 mots/min)
$motsHero = $hero ? str_word_count(strip_tags($hero['contenu'])) : 0;
$lectureHero = max(1, round($motsHero / 200));

// 2 articles suivants pour les cards (3 au total avec le hero)
$stmtCards = $pdo->prepare("
    SELECT a.*, r.nom AS rubrique
    FROM articles a
    LEFT JOIN rubriques r ON a.rubrique_id = r.id
    WHERE a.statut = 'publie' AND a.id != :id
    ORDER BY a.date_publication DESC
    LIMIT 2
");
$stmtCards->execute([':id' => $hero['id'] ?? 0]);
$cards = $stmtCards->fetchAll();

// Termes du lexique
$lexique = $pdo->query("SELECT terme, categorie FROM lexique ORDER BY terme ASC")->fetchAll();

// Articles à venir
$stmtAVenir = $pdo->query("
    SELECT a.*, r.nom AS rubrique, au.nom AS auteur
    FROM articles a
    LEFT JOIN rubriques r  ON a.rubrique_id = r.id
    LEFT JOIN auteurs   au ON a.auteur_id   = au.id
    WHERE a.statut = 'a_venir'
    ORDER BY a.date_creation DESC
    LIMIT 1
");
$articleAVenir = $stmtAVenir->fetch();

// Image newsletter
$mediaNewsletter = $pdo->query("
    SELECT fichier, alt
    FROM medias
    WHERE contexte = 'newsletter'
    LIMIT 1
")->fetch();

// Gestion inscription newsletter
$newsletterMessage = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['email'])) {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO newsletter (email) VALUES (:email)");
            $stmt->execute([':email' => $email]);
            $newsletterMessage = ['type' => 'succes', 'texte' => 'Vous êtes bien inscrit à la newsletter.'];
        } catch (PDOException $e) {
            if ($e->getCode() === '23000') {
                $newsletterMessage = ['type' => 'erreur', 'texte' => 'Cet email est déjà inscrit.'];
            } else {
                $newsletterMessage = ['type' => 'erreur', 'texte' => 'Une erreur est survenue, veuillez réessayer.'];
            }
        }
    } else {
        $newsletterMessage = ['type' => 'erreur', 'texte' => 'Adresse e-mail invalide.'];
    }
}

// Le render

echo $twig->render('index.html.twig', [
    'hero' => $hero,
    'cards' => $cards,
    'lexique' => $lexique,
    'lectureHero' => $lectureHero,
    'articleAVenir' => $articleAVenir,
    'mediaNewsletter' => $mediaNewsletter,
    'newsletterMessage' => $newsletterMessage,
    'base' => $_ENV['BASE_URL'] ?? '',

]);