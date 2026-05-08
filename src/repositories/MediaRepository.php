<?php
class MediaRepository
{
    public function __construct(private PDO $pdo)
    {
    }

    /** Récupère le média associé à la newsletter */
    public function findNewsletterMedia(): ?array
    {
        $stmt = $this->pdo->query("
            SELECT fichier, alt
            FROM medias
            WHERE contexte = 'newsletter'
            LIMIT 1
        ");
        return $stmt->fetch() ?: null;
    }
}