<?php
require_once 'config/db.php';

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

// 4 articles suivants pour les cards
$stmtCards = $pdo->prepare("
    SELECT a.*, r.nom AS rubrique
    FROM articles a
    LEFT JOIN rubriques r ON a.rubrique_id = r.id
    WHERE a.statut = 'publie' AND a.id != :id
    ORDER BY a.date_publication DESC
    LIMIT 4
");
$stmtCards->execute([':id' => $hero['id'] ?? 0]);
$cards = $stmtCards->fetchAll();

// Termes du lexique
$lexique = $pdo->query("SELECT terme, categorie FROM lexique ORDER BY terme ASC")->fetchAll();
?>
<?php require_once 'includes/header.php'; ?>

<main class="w-5/6 mx-auto">
    <section class="h-screen mt-20">

        <article>
            <a href="<?= $base ?>/pages/article.php?slug=<?= htmlspecialchars($hero['slug']) ?>"
                class="grid grid-cols-1 lg:grid-cols-2 gap-10 lg:gap-50">
                <div class="flex flex-col justify-between gap-10 lg:gap-0">

                    <div class="flex flex-col gap-5">
                        <h1>
                            <?= htmlspecialchars($hero['titre']) ?>
                        </h1>
                        <img src="<?= $base ?>/assets/images/<?= $hero['image_principale'] ?>"
                            class="w-full h-full object-cover aspect-3/2 overflow-hidden md:hidden">
                        <small><?= htmlspecialchars($hero['chapeau']) ?></small>
                    </div>

                    <div class="flex justify-between">
                        <small><?= formatDateFr($hero['date_publication']) ?></small>
                        <small class="text-neutral-400"><?= $hero['rubrique'] ?></small>
                    </div>
                </div>

                <div class="hidden lg:block flex flex-col gap-5">
                    <img src="<?= $base ?>/assets/images/<?= $hero['image_principale'] ?>"
                        class="w-full h-full object-cover aspect-square overflow-hidden">
                    <small class="text-neutral-400"><?= $hero['credit_photo'] ?></small>
                </div>
            </a>
        </article>

        <div>
            <article></article>
            <article></article>
        </div>
    </section>

</main>

<?php require_once 'includes/footer.php'; ?>