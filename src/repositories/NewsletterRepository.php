<?php

class NewsletterRepository
{
    public function __construct(private PDO $pdo)
    {
    }

    /** Récupère tous les inscrits triés par date d'inscription décroissante */
    public function findAll(): array
    {
        return $this->pdo->query('SELECT * FROM newsletter ORDER BY date_inscription DESC')->fetchAll();
    }

    /** Insère un nouvel email dans la newsletter */
    public function insert(string $email): void
    {
        $stmt = $this->pdo->prepare('INSERT INTO newsletter (email) VALUES (:email)');
        $stmt->execute([':email' => $email]);
    }

    /** Supprime un inscrit par son id */
    public function delete(int $id): void
    {
        $stmt = $this->pdo->prepare('DELETE FROM newsletter WHERE id = ?');
        $stmt->execute([$id]);
    }
}