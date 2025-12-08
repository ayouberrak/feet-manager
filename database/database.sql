
CREATE TABLE COURS (
    id_cours INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(100) NOT NULL,
    categorie VARCHAR(50) NOT NULL,
    date_cours DATE NOT NULL,
    heure_cours TIME NOT NULL,
    duree INT NOT NULL, 
    max_participants INT NOT NULL DEFAULT 20,
    CHECK (duree > 0 AND max_participants > 0)
);


CREATE TABLE EQUIPEMENT (
    id_equipement INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(100) NOT NULL,
    type_equipement VARCHAR(50) NOT NULL,
    quantite_disponible INT NOT NULL,
    etat ENUM('bon', 'moyen', 'a remplacer') NOT NULL DEFAULT 'bon',
    CHECK (quantite_disponible >= 0)
);

CREATE TABLE COURS_EQUIPEMENT (
    id_cours INT NOT NULL,
    id_equipement INT NOT NULL,

    PRIMARY KEY (id_cours, id_equipement),

    FOREIGN KEY (id_cours) 
        REFERENCES COURS(id_cours) 
        ON DELETE CASCADE 
        ON UPDATE CASCADE,
        
    FOREIGN KEY (id_equipement) 
        REFERENCES EQUIPEMENT(id_equipement) 
        ON DELETE CASCADE 
        ON UPDATE CASCADE
);


INSERT INTO COURS (nom, categorie, date_cours, heure_cours, duree, max_participants) VALUES
('Yoga Sérénité', 'Yoga', '2025-12-15', '19:00:00', 60, 18),
('Haltères Avancé', 'Musculation', '2025-12-16', '11:00:00', 90, 12),
('Zumba Party', 'Cardio', '2025-12-15', '17:30:00', 45, 30),
('Pilates Fondamental', 'Yoga', '2025-12-17', '09:00:00', 60, 15);

INSERT INTO EQUIPEMENT (nom, type_equipement, quantite_disponible, etat) VALUES
('Tapis de course', 'Cardio', 15, 'bon'),
('Kettlebells (10kg)', 'Force', 20, 'moyen'),
('Tapis de sol', 'Accessoire', 50, 'bon'),
('Cordes à sauter', 'Cardio', 30, 'bon'),
('Bandes de résistance', 'Accessoire', 40, 'bon');

INSERT INTO COURS_EQUIPEMENT (id_cours, id_equipement) VALUES (1, 3);
INSERT INTO COURS_EQUIPEMENT (id_cours, id_equipement) VALUES (2, 2);
INSERT INTO COURS_EQUIPEMENT (id_cours, id_equipement) VALUES (4, 3);
INSERT INTO COURS_EQUIPEMENT (id_cours, id_equipement) VALUES (4, 5);



CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL,
    pass VARCHAR(255) NOT NULL
);

