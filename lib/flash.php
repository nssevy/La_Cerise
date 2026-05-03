<?php
// lib/flash.php

function flash_success(string $message): void
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    $_SESSION['flash_success'] = $message;
}

function flash_error(string $message): void
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    $_SESSION['flash_error'] = $message;
}

function get_flash(): array
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $flash = [
        'flash_success' => $_SESSION['flash_success'] ?? null,
        'flash_error' => $_SESSION['flash_error'] ?? null,
    ];

    unset($_SESSION['flash_success'], $_SESSION['flash_error']);

    return $flash;
}