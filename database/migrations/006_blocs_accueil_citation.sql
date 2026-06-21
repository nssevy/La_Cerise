-- Redesign page d'accueil : contenus éditables depuis l'admin
-- (section « Pourquoi La Cerise » + citation animée du bas de page)

CREATE TABLE
    blocs_accueil (
        id INT PRIMARY KEY AUTO_INCREMENT,
        position INT NOT NULL,
        contenu TEXT,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    );

INSERT INTO
    blocs_accueil (position, contenu)
VALUES
    (
        1,
        "Comme le fruit qu'on met sur le gâteau : parce que le droit international, c'est souvent ce détail décisif qu'on oublie d'expliquer et qui change tout."
    ),
    (
        2,
        "Comme une couleur qui ne passe pas inaperçue : parce que les crises qu'on traverse méritent une analyse qui tranche, qui prend position, qui ne se fond pas dans le bruit ambiant."
    ),
    (
        3,
        "Comme quelque chose d'acide et de doux à la fois : parce qu'on croit qu'on peut parler de justice, de conflits et de droit humanitaire avec rigueur sans perdre l'envie de comprendre."
    );

CREATE TABLE
    citation_accueil (
        id INT PRIMARY KEY AUTO_INCREMENT,
        contenu TEXT,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    );

INSERT INTO
    citation_accueil (contenu)
VALUES
    (
        "Notre objectif n'est pas de rendre le droit neutre. C'est de le rendre lisible, pour ceux qui veulent comprendre le monde tel qu'il est."
    );
