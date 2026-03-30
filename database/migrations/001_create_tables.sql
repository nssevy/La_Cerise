CREATE TABLE rubriques (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    slug VARCHAR(100) NOT NULL UNIQUE,
    description TEXT
);

CREATE TABLE auteurs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    bio TEXT,
    email VARCHAR(150) UNIQUE
);

CREATE TABLE articles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titre VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    chapeau TEXT,
    contenu LONGTEXT NOT NULL,
    image_principale VARCHAR(255),
    statut ENUM('brouillon', 'publie', 'archive') DEFAULT 'brouillon',
    date_publication DATETIME,
    date_creation DATETIME DEFAULT CURRENT_TIMESTAMP,
    rubrique_id INT,
    auteur_id INT,
    FOREIGN KEY (rubrique_id) REFERENCES rubriques(id),
    FOREIGN KEY (auteur_id) REFERENCES auteurs(id)
);

CREATE TABLE lexique (
    id INT AUTO_INCREMENT PRIMARY KEY,
    terme VARCHAR(150) NOT NULL,
    definition TEXT NOT NULL,
    categorie VARCHAR(100),
    date_creation DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    mot_de_passe VARCHAR(255) NOT NULL,
    role ENUM('admin', 'editeur') DEFAULT 'editeur',
    date_creation DATETIME DEFAULT CURRENT_TIMESTAMP
);