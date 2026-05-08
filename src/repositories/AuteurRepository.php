<?php

class AuteurRepository
{
    public function __construct(private PDO $pdo)
    {
    }

    /** Récupère tous les auteurs triés par nom */
    public function findAll(): array
    {
        return $this->pdo->query('SELECT id, nom, bio, email FROM auteurs ORDER BY nom')->fetchAll();
    }

    /** Récupère un auteur par son id */
    public function findById(int $id): ?array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM auteurs WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    /** Vérifie si l'auteur existe par son id */
    public function exists(int $id): bool
    {
        $stmt = $this->pdo->prepare('SELECT id FROM auteurs WHERE id = ?');
        $stmt->execute([$id]);
        return (bool) $stmt->fetch();
    }

    /** Récupère les titres des articles liés à un auteur */
    public function findLinkedArticles(int $auteurId): array
    {
        $stmt = $this->pdo->prepare('SELECT titre FROM articles WHERE auteur_id = ?');
        $stmt->execute([$auteurId]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    /** Insère un nouvel auteur */
    public function insert(string $nom, ?string $bio, ?string $email): void
    {
        $stmt = $this->pdo->prepare('INSERT INTO auteurs (nom, bio, email) VALUES (?, ?, ?)');
        $stmt->execute([$nom, $bio, $email]);
    }

    /** Met à jour un auteur existant */
    public function update(int $id, string $nom, ?string $bio, ?string $email): void
    {
        $stmt = $this->pdo->prepare('UPDATE auteurs SET nom = ?, bio = ?, email = ? WHERE id = ?');
        $stmt->execute([$nom, $bio, $email, $id]);
    }

    /** Supprime un auteur par son id */
    public function delete(int $id): void
    {
        $stmt = $this->pdo->prepare('DELETE FROM auteurs WHERE id = ?');
        $stmt->execute([$id]);
    }
}