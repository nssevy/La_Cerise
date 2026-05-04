-- 1. Création de la table categories
CREATE TABLE
    categories (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nom VARCHAR(255) NOT NULL
    );

-- 2. Insertion des catégories
INSERT INTO
    categories (id, nom)
VALUES
    (1, 'Institutions internationales'),
    (2, 'Droit international'),
    (3, 'Droit humanitaire');

-- 3. Suppression de la colonne categorie (texte libre) et ajout de categorie_id
ALTER TABLE lexique
DROP COLUMN categorie;

ALTER TABLE lexique
ADD COLUMN categorie_id INT NULL;

ALTER TABLE lexique ADD CONSTRAINT fk_lexique_categorie FOREIGN KEY (categorie_id) REFERENCES categories (id) ON DELETE SET NULL;

-- 4. Mise à jour des termes
UPDATE lexique
SET
    categorie_id = 1
WHERE
    id IN (1, 2, 3, 5, 15, 16, 17);

UPDATE lexique
SET
    categorie_id = 2
WHERE
    id IN (4, 6, 7, 8, 9, 10, 12, 13);

UPDATE lexique
SET
    categorie_id = 3
WHERE
    id IN (11, 14);