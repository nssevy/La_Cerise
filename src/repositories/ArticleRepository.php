<?php

class ArticleRepository
{
    public function __construct(private PDO $pdo)
    {
    }

    /** Récupère un article publié par son slug */
    public function findBySlug(string $slug): ?array
    {
        $stmt = $this->pdo->prepare('
            SELECT articles.*, auteurs.nom AS auteur_nom, rubriques.nom AS rubrique_nom
            FROM articles
            LEFT JOIN auteurs ON articles.auteur_id = auteurs.id
            LEFT JOIN rubriques ON articles.rubrique_id = rubriques.id
            WHERE articles.slug = :slug
            AND articles.statut = "publie"
        ');
        $stmt->execute([':slug' => $slug]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    /** Récupère N articles publiés en excluant l'article courant */
    public function findSuggested(int $excludeId, int $limit = 3): array
    {
        $stmt = $this->pdo->prepare('
            SELECT articles.*, auteurs.nom AS auteur_nom, rubriques.nom AS rubrique_nom
            FROM articles
            LEFT JOIN auteurs ON articles.auteur_id = auteurs.id
            LEFT JOIN rubriques ON articles.rubrique_id = rubriques.id
            WHERE articles.statut = "publie"
            AND articles.id != :id
            ORDER BY articles.date_publication DESC
            LIMIT ' . (int) $limit
        );
        $stmt->execute([':id' => $excludeId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /** Récupère l'article publié le plus récent pour le hero */
    public function findHero(): ?array
    {
        $stmt = $this->pdo->query("
            SELECT a.*, r.nom AS rubrique, au.nom AS auteur
            FROM articles a
            LEFT JOIN rubriques r  ON a.rubrique_id = r.id
            LEFT JOIN auteurs   au ON a.auteur_id   = au.id
            WHERE a.statut = 'publie'
            ORDER BY a.date_publication DESC
            LIMIT 1
        ");
        return $stmt->fetch() ?: null;
    }

    /** Récupère N articles publiés pour les cards en excluant le hero */
    public function findCards(int $excludeId, int $limit = 2): array
    {
        $stmt = $this->pdo->prepare("
            SELECT a.*, r.nom AS rubrique
            FROM articles a
            LEFT JOIN rubriques r ON a.rubrique_id = r.id
            WHERE a.statut = 'publie' AND a.id != :id
            ORDER BY a.date_publication DESC
            LIMIT " . (int) $limit
        );
        $stmt->execute([':id' => $excludeId]);
        return $stmt->fetchAll();
    }

    /** Récupère le prochain article à venir */
    public function findUpcoming(): ?array
    {
        $stmt = $this->pdo->query("
            SELECT a.*, r.nom AS rubrique, au.nom AS auteur
            FROM articles a
            LEFT JOIN rubriques r  ON a.rubrique_id = r.id
            LEFT JOIN auteurs   au ON a.auteur_id   = au.id
            WHERE a.statut = 'a_venir'
            ORDER BY a.date_creation DESC
            LIMIT 1
        ");
        return $stmt->fetch() ?: null;
    }

    /** Récupère tous les articles pour le dashboard admin */
    public function findAllAdmin(): array
    {
        $stmt = $this->pdo->query('
            SELECT a.id, a.titre, a.statut, a.date_publication, a.date_creation, a.chapeau,
                   a.image_principale,
                   r.nom AS rubrique
            FROM articles a
            LEFT JOIN rubriques r ON a.rubrique_id = r.id
            ORDER BY a.date_creation DESC
        ');
        return $stmt->fetchAll();
    }

    /** Retourne le nombre d'articles groupés par statut */
    public function getStats(): array
    {
        $stmt = $this->pdo->query('
            SELECT statut, COUNT(*) as total
            FROM articles
            GROUP BY statut
        ');
        return $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
    }

    /** Récupère un article par son id */
    public function findById(int $id): ?array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM articles WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    /** Récupère toutes les rubriques triées par nom */
    public function findRubriques(): array
    {
        return $this->pdo->query('SELECT id, nom, description FROM rubriques ORDER BY nom')->fetchAll();
    }

    /** Récupère tous les auteurs triés par nom */
    public function findAuteurs(): array
    {
        return $this->pdo->query('SELECT id, nom FROM auteurs ORDER BY nom')->fetchAll();
    }

    /** Insère un nouvel article */
    public function insert(array $data): void
    {
        $stmt = $this->pdo->prepare('
            INSERT INTO articles (titre, slug, chapeau, contenu, image_principale, credit_photo, statut, date_publication, rubrique_id, auteur_id)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ');
        $stmt->execute([
            $data['titre'],
            $data['slug'],
            $data['chapeau'],
            $data['contenu'],
            $data['image_principale'],
            $data['credit_photo'],
            $data['statut'],
            $data['date_publication'],
            $data['rubrique_id'],
            $data['auteur_id'],
        ]);
    }

    /** Met à jour un article existant */
    public function update(int $id, array $data): void
    {
        $stmt = $this->pdo->prepare('
            UPDATE articles
            SET titre = ?, slug = ?, chapeau = ?, contenu = ?, image_principale = ?,
                credit_photo = ?, statut = ?, date_publication = ?, rubrique_id = ?, auteur_id = ?
            WHERE id = ?
        ');
        $stmt->execute([
            $data['titre'],
            $data['slug'],
            $data['chapeau'],
            $data['contenu'],
            $data['image_principale'],
            $data['credit_photo'],
            $data['statut'],
            $data['date_publication'],
            $data['rubrique_id'],
            $data['auteur_id'],
            $id,
        ]);
    }

    /** Met à jour uniquement le statut d'un article */
    public function updateStatut(int $id, string $statut): void
    {
        $stmt = $this->pdo->prepare('UPDATE articles SET statut = ? WHERE id = ?');
        $stmt->execute([$statut, $id]);
    }

    /** Supprime un article par son id */
    public function delete(int $id): void
    {
        $stmt = $this->pdo->prepare('DELETE FROM articles WHERE id = ?');
        $stmt->execute([$id]);
    }
    /** Récupère tous les articles publiés, ordonnés par date décroissante */
    public function findAllPublished(): array
    {
        $stmt = $this->pdo->query("
            SELECT a.*, r.nom AS rubrique, au.nom AS auteur
            FROM articles a
            LEFT JOIN rubriques r  ON a.rubrique_id = r.id
            LEFT JOIN auteurs   au ON a.auteur_id   = au.id
            WHERE a.statut = 'publie'
            ORDER BY a.date_publication DESC
        ");
        return $stmt->fetchAll();
    }

    /** Récupère les N derniers articles publiés pour la grille homepage */
    public function findLatestPublished(int $limit = 4): array
    {
        $stmt = $this->pdo->query("
        SELECT a.*, r.nom AS rubrique, au.nom AS auteur
        FROM articles a
        LEFT JOIN rubriques r  ON a.rubrique_id = r.id
        LEFT JOIN auteurs   au ON a.auteur_id   = au.id
        WHERE a.statut = 'publie'
        ORDER BY a.date_publication DESC
        LIMIT " . (int) $limit
        );
        return $stmt->fetchAll();
    }

    /** Récupère tous les articles publiés en excluant une liste d'IDs */
    public function findExcluding(array $excludeIds): array
    {
        $placeholders = empty($excludeIds) ? '0' : implode(',', array_map('intval', $excludeIds));

        $stmt = $this->pdo->query("
            SELECT a.*, r.nom AS rubrique, au.nom AS auteur
            FROM articles a
            LEFT JOIN rubriques r  ON a.rubrique_id = r.id
            LEFT JOIN auteurs   au ON a.auteur_id   = au.id
            WHERE a.statut = 'publie'
            AND a.id NOT IN ($placeholders)
            ORDER BY a.date_publication DESC
        ");
        return $stmt->fetchAll();
    }
}