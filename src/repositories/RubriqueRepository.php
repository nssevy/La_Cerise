<?php

class RubriqueRepository
{
    public function __construct(private PDO $pdo) {}

    public function findAll(): array
    {
        return $this->pdo->query('SELECT id, nom, description FROM rubriques ORDER BY nom')->fetchAll();
    }
}
