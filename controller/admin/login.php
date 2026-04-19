<?php
require_once dirname(__DIR__, 2) . '/vendor/autoload.php';
require_once dirname(__DIR__, 2) . '/config/db.php';
require_once dirname(__DIR__, 2) . '/config/twig.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!empty($_SESSION['user_id'])) {
    $base = $_ENV['BASE_URL'] ?? '';
    header('Location: ' . $base . '/admin/dashboard');
    exit;
}

$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email === '' || $password === '') {
        $error = 'Tous les champs sont obligatoires.';
    } else {
        $stmt = $pdo->prepare('SELECT * FROM users WHERE (email = ? OR nom = ?) AND role = "admin" LIMIT 1');
        $stmt->execute([$email, $email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['mot_de_passe'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_nom'] = $user['nom'];
            $_SESSION['user_role'] = $user['role'];

            $base = $_ENV['BASE_URL'] ?? '';
            header('Location: ' . $base . '/admin/dashboard');
            exit;
        } else {
            $error = 'Email, Nom ou mot de passe incorrect.';
        }
    }
}

echo $twig->render('admin/login.html.twig', [
    'error' => $error,
    'base' => $_ENV['BASE_URL'] ?? ''

]);