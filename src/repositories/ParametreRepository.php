<?php

class ParametreRepository
{
    public function __construct(private PDO $pdo)
    {
    }

    /** Récupère un utilisateur par son id */
    public function findUser(int $id): ?array
    {
        $stmt = $this->pdo->prepare('SELECT id, nom, email FROM users WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    /** Met à jour le profil sans changer le mot de passe */
    public function updateUser(int $id, string $nom, string $email): void
    {
        $stmt = $this->pdo->prepare('UPDATE users SET nom = ?, email = ? WHERE id = ?');
        $stmt->execute([$nom, $email, $id]);
    }

    /** Met à jour le profil avec un nouveau mot de passe */
    public function updateUserWithPassword(int $id, string $nom, string $email, string $hashedPassword): void
    {
        $stmt = $this->pdo->prepare('UPDATE users SET nom = ?, email = ?, mot_de_passe = ? WHERE id = ?');
        $stmt->execute([$nom, $email, $hashedPassword, $id]);
    }

    /** Récupère toutes les pages légales */
    public function findPagesLegales(): array
    {
        return $this->pdo->query('SELECT type, titre FROM pages_legales ORDER BY id')->fetchAll();
    }

    /** Récupère une page légale par son type */
    public function findPageLegaleByType(string $type): ?array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM pages_legales WHERE type = ?');
        $stmt->execute([$type]);
        return $stmt->fetch() ?: null;
    }

    /** Met à jour une page légale */
    public function updatePageLegale(string $type, string $titre, string $contenu): void
    {
        $stmt = $this->pdo->prepare('UPDATE pages_legales SET titre = ?, contenu = ? WHERE type = ?');
        $stmt->execute([$titre, $contenu, $type]);
    }
}