<?php
$base = rtrim(str_replace($_SERVER['DOCUMENT_ROOT'], '', dirname(__DIR__)), '/\\');
$pageTitle = $pageTitle ?? 'La Cerise';
require_once __DIR__ . '/../includes/helpers.php';
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle) ?></title>
    <link rel="stylesheet" href="<?= $base ?>/assets/css/output.css">
    <!-- Les favicons -->
    <link rel="icon" type="image/x-icon" href="<?= $base ?>/assets/images/favicon/favicon.ico">
    <link rel="icon" type="image/png" sizes="32x32" href="<?= $base ?>/assets/images/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="<?= $base ?>/assets/images/favicon/favicon-16x16.png">
    <link rel="apple-touch-icon" sizes="180x180" href="<?= $base ?>/assets/images/favicon/apple-touch-icon.png">
    <!-- Lucide icons -->
    <link rel="stylesheet" href="https://unpkg.com/lucide-static@latest/font/lucide.css">
</head>

<body>

    <header class="w-full sticky top-0 z-50 bg-white border-b border-[var(--09)]">

        <div class="flex items-center justify-between px-12 py-4">

            <a href="<?= $base ?>/">
                <img src="<?= $base ?>/assets/images/Logo.png" alt="La Cerise" class="h-8 w-auto">
            </a>

            <!-- Navigation desktop -->
            <nav class="hidden md:flex items-center gap-5 text-body uppercase text-[var(--01)] whitespace-nowrap">
                <a href="#" class="hover:text-[var(--special)] transition-colors">S'abonner</a>
                <a href="#" class="hover:text-[var(--special)] transition-colors">Articles</a>
                <a href="#" class="hover:text-[var(--special)] transition-colors">Lexique</a>
            </nav>

            <!-- Me contacter + hamburger -->
            <div class="flex items-center gap-4">
                <a href="#"
                    class="hidden md:block text-body uppercase text-[var(--01)] whitespace-nowrap hover:text-[var(--special)] transition-colors">
                    Me contacter
                </a>

                <!-- Bouton hamburger (mobile uniquement) -->
                <button id="menu-btn" class="md:hidden" aria-label="Ouvrir le menu" aria-expanded="false">
                    <div id="icon-open" class="icon-menu text-2xl"></div>
                    <div id="icon-close" class="icon-x text-2xl hidden"></div>
                </button>
            </div>

        </div>

        <!-- Menu mobile -->
        <nav id="mobile-menu"
            class="hidden md:hidden flex-col gap-4 px-12 pb-6 pt-2 border-t border-[var(--09)] bg-white">
            <a href="#"
                class="block text-body uppercase text-[var(--01)] py-2 hover:text-[var(--special)] transition-colors">
                S'abonner
            </a>
            <a href="#"
                class="block text-body uppercase text-[var(--01)] py-2 hover:text-[var(--special)] transition-colors">
                Articles
            </a>
            <a href="#"
                class="block text-body uppercase text-[var(--01)] py-2 hover:text-[var(--special)] transition-colors">
                Lexique
            </a>
            <a href="#"
                class="block text-body uppercase text-[var(--01)] py-2 hover:text-[var(--special)] transition-colors">
                Me contacter
            </a>
        </nav>

    </header>

    <script src="<?= $base ?>/assets/js/menu.js" defer></script>