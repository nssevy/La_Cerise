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

    /** Vérifie si un slug est déjà utilisé (en option, en excluant un id) */
    public function slugExists(string $slug, ?int $excludeId = null): bool
    {
        $sql = 'SELECT id FROM rubriques WHERE slug = ?';
        $params = [$slug];
        if ($excludeId !== null) {
            $sql .= ' AND id <> ?';
            $params[] = $excludeId;
        }
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return (bool) $stmt->fetch();
    }

    /** Génère un slug unique à partir d'un nom (suffixe -2, -3… si collision) */
    public function generateUniqueSlug(string $nom, ?int $excludeId = null): string
    {
        $base = slugify($nom);
        $slug = $base;
        $i = 2;
        while ($this->slugExists($slug, $excludeId)) {
            $slug = $base . '-' . $i;
            $i++;
        }
        return $slug;
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
