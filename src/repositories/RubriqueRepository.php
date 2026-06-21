<?php

class RubriqueRepository
{
    public function __construct(private PDO $pdo) {}

    /** Récupère toutes les rubriques triées par nom */
    public function findAll(): array
    {
        return $this->pdo->query('SELECT id, nom, description FROM rubriques ORDER BY nom')->fetchAll();
    }

    /** Récupère une rubrique par son id */
    public function findById(int $id): ?array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM rubriques WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    /** Vérifie si la rubrique existe par son id */
    public function exists(int $id): bool
    {
        $stmt = $this->pdo->prepare('SELECT id FROM rubriques WHERE id = ?');
        $stmt->execute([$id]);
        return (bool) $stmt->fetch();
    }

    /** Récupère les titres des articles liés à une rubrique */
    public function findLinkedArticles(int $rubriqueId): array
    {
        $stmt = $this->pdo->prepare('SELECT titre FROM articles WHERE rubrique_id = ?');
        $stmt->execute([$rubriqueId]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    /** Insère une nouvelle rubrique */
    public function insert(string $nom, string $slug, ?string $description): void
    {
        $stmt = $this->pdo->prepare('INSERT INTO rubriques (nom, slug, description) VALUES (?, ?, ?)');
        $stmt->execute([$nom, $slug, $description]);
    }

    /** Met à jour une rubrique existante */
    public function update(int $id, string $nom, string $slug, ?string $description): void
    {
        $stmt = $this->pdo->prepare('UPDATE rubriques SET nom = ?, slug = ?, description = ? WHERE id = ?');
        $stmt->execute([$nom, $slug, $description, $id]);
    }

    /** Supprime une rubrique par son id */
    public function delete(int $id): void
    {
        $stmt = $this->pdo->prepare('DELETE FROM rubriques WHERE id = ?');
        $stmt->execute([$id]);
    }
}
