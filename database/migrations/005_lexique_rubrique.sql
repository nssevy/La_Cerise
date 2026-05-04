ALTER TABLE lexique DROP COLUMN categorie;
ALTER TABLE lexique ADD COLUMN rubrique_id INT NULL;
ALTER TABLE lexique ADD CONSTRAINT fk_lexique_rubrique FOREIGN KEY (rubrique_id) REFERENCES rubriques(id) ON DELETE SET NULL;
