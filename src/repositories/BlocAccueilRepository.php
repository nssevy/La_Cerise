<?php

class BlocAccueilRepository
{
    public function __construct(private PDO $pdo) {}

    /** Récupère tous les blocs triés par position */
    public function findAll(): array
    {
        return $this->pdo->query('SELECT id, position, contenu FROM blocs_accueil ORDER BY position')->fetchAll();
    }

    /** Récupère un bloc par son id */
    public function findById(int $id): ?array
    {
        $stmt = $this->pdo->prepare('SELECT id, position, contenu FROM blocs_accueil WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    /** Met à jour le contenu d'un bloc */
    public function update(int $id, ?string $contenu): void
    {
        $stmt = $this->pdo->prepare('UPDATE blocs_accueil SET contenu = ? WHERE id = ?');
        $stmt->execute([$contenu, $id]);
    }
}
