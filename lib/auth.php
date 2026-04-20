<?php

function requireLogin(): void
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (empty($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
        $base = $_ENV['BASE_URL'] ?? '';
        header('Location: ' . $base . '/admin/login');
        exit;
    }
}

function logout(): void
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    session_destroy();
    $base = $_ENV['BASE_URL'] ?? '';
    header('Location: ' . $base . '/admin/login');
    exit;
}
