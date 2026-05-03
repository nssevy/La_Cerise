CREATE TABLE
    pages_legales (
        id INT PRIMARY KEY AUTO_INCREMENT,
        type ENUM ('confidentialite', 'mentions_legales', 'cgu') NOT NULL UNIQUE,
        titre VARCHAR(255) NOT NULL,
        contenu LONGTEXT NOT NULL,
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    );