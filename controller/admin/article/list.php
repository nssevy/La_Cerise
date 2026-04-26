<?php
// Le Heredoc est une syntaxe qui permet d'écrire une string multiligne sans se soucier des caractères spéciaux. Le nom vient de "Here Document" littéralement "le document est ici, dans le code".

// La syntaxe <<<SQL ... SQL dit au PHP : "tout ce qui est entre ces deux marqueurs est une string, peu importe ce qu'il y a dedans". Le marqueur peut s'appeler n'importe quoi (SQL, HTML, TEXT...), c'est juste un délimiteur de début et de fin.

// C'est une convention présente dans beaucoup de langages (PHP, Bash, Ruby, etc.) pour embarquer proprement un bloc de texte long dans du code sans pollution syntaxique.
require_once dirname(__DIR__, 3) . '/vendor/autoload.php';
require_once dirname(__DIR__, 3) . '/config/db.php';
require_once dirname(__DIR__, 3) . '/config/twig.php';
require_once dirname(__DIR__, 3) . '/lib/auth.php';

requireLogin();

$stmt = $pdo->query(<<<SQL
    SELECT a.id, a.titre, a.statut, a.date_publication, a.date_creation, a.chapeau,
           a.image_principale,
           r.nom AS rubrique
    FROM articles a
    LEFT JOIN rubriques r ON a.rubrique_id = r.id
    ORDER BY a.date_creation DESC
SQL);

$articles = $stmt->fetchAll();

$stmtStats = $pdo->query(<<<SQL
    SELECT statut, COUNT(*) as total
    FROM articles
    GROUP BY statut
SQL);

$stats = $stmtStats->fetchAll(PDO::FETCH_KEY_PAIR);

echo $twig->render('admin/dashboard.html.twig', [
    'articles' => $articles,
    'stats' => $stats,
    'user_nom' => $_SESSION['user_nom'],
    'base' => $_ENV['BASE_URL'] ?? '',
    'section' => 'articles'
]);