<?php

class LexiqueRepository
{
    public function __construct(private PDO $pdo)
    {
    }

    /** Récupère tous les termes avec leur catégorie pour la page publique */
    public function findAllPublic(): array
    {
        return $this->pdo->query("
            SELECT l.terme, l.definition, c.nom AS categorie
            FROM lexique l
            LEFT JOIN categories c ON l.categorie_id = c.id
            ORDER BY l.terme ASC
        ")->fetchAll(PDO::FETCH_ASSOC);
    }

    /** Récupère tous les termes pour l'admin, avec filtre optionnel par catégorie */
    public function findAllAdmin(?int $categorieId = null): array
    {
        if ($categorieId) {
            $stmt = $this->pdo->prepare('
                SELECT l.*, c.nom AS categorie_nom
                FROM lexique l
                LEFT JOIN categories c ON l.categorie_id = c.id
                WHERE l.categorie_id = ?
                ORDER BY l.terme ASC
            ');
            $stmt->execute([$categorieId]);
        } else {
            $stmt = $this->pdo->query('
                SELECT l.*, c.nom AS categorie_nom
                FROM lexique l
                LEFT JOIN categories c ON l.categorie_id = c.id
                ORDER BY l.terme ASC
            ');
        }
        return $stmt->fetchAll();
    }

    /** Récupère un terme par son id avec sa catégorie */
    public function findById(int $id): ?array
    {
        $stmt = $this->pdo->prepare('
            SELECT l.*, c.nom AS categorie_nom
            FROM lexique l
            LEFT JOIN categories c ON l.categorie_id = c.id
            WHERE l.id = ?
        ');
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    /** Récupère toutes les catégories triées par nom */
    public function findCategories(): array
    {
        return $this->pdo->query('SELECT * FROM categories ORDER BY nom ASC')->fetchAll();
    }

    /** Insère un nouveau terme dans le lexique */
    public function insert(string $terme, string $definition, ?int $categorieId): void
    {
        $stmt = $this->pdo->prepare('INSERT INTO lexique (terme, definition, categorie_id) VALUES (?, ?, ?)');
        $stmt->execute([$terme, $definition, $categorieId]);
    }

    /** Met à jour un terme existant */
    public function update(int $id, string $terme, string $definition, ?int $categorieId): void
    {
        $stmt = $this->pdo->prepare('UPDATE lexique SET terme = ?, definition = ?, categorie_id = ? WHERE id = ?');
        $stmt->execute([$terme, $definition, $categorieId, $id]);
    }

    /** Supprime un terme par son id */
    public function delete(int $id): void
    {
        $stmt = $this->pdo->prepare('DELETE FROM lexique WHERE id = ?');
        $stmt->execute([$id]);
    }
}