<?php

class CitationRepository
{
    public function __construct(private PDO $pdo) {}

    /** Récupère la citation (unique ligne) */
    public function find(): ?array
    {
        return $this->pdo->query('SELECT id, contenu FROM citation_accueil ORDER BY id LIMIT 1')->fetch() ?: null;
    }

    /** Met à jour le contenu de la citation */
    public function update(int $id, ?string $contenu): void
    {
        $stmt = $this->pdo->prepare('UPDATE citation_accueil SET contenu = ? WHERE id = ?');
        $stmt->execute([$contenu, $id]);
    }
}
