
CREATE TABLE COURS (
  id_cours INT AUTO_INCREMENT PRIMARY KEY,
  nom VARCHAR(100) NOT NULL,
  categorie VARCHAR(50) NOT NULL,
  date_cours DATE NOT NULL,
  heure_debut TIME NOT NULL,
  duree_min INT NOT NULL CHECK (duree_min > 0),
  max_participants INT NOT NULL CHECK (max_participants >= 1),
  UNIQUE (date_cours, heure_debut)
);

CREATE TABLE EQUIPEMENT (
  id_equipement INT AUTO_INCREMENT PRIMARY KEY,
  nom VARCHAR(100) NOT NULL,
  type VARCHAR(50) NOT NULL,
  quantite_disponible INT NOT NULL DEFAULT 0 CHECK (quantite_disponible >= 0),
  etat ENUM('Bon', 'Moyen', 'À remplacer') NOT NULL
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

INSERT INTO COURS (nom, categorie, date_cours, heure_debut, duree_min, max_participants) VALUES
('Hatha Flow', 'Yoga', '2025-12-10', '18:00:00', 60, 15),
('Body Pump', 'Musculation', '2025-12-10', '19:15:00', 45, 20),
('RPM - Cycling', 'Cardio', '2025-12-11', '07:30:00', 50, 10);

INSERT INTO EQUIPEMENT (nom, type, quantite_disponible, etat) VALUES
('Tapis de Course Modèle A', 'Tapis de course', 12, 'Bon'),
('Haltères 5kg', 'Haltères', 30, 'Moyen'),
('Ballon de Yoga', 'Ballons', 15, 'Bon');

INSERT INTO COURS_EQUIPEMENT (id_cours, id_equipement, quantite_requise) VALUES
(2, 2, 20); 

INSERT INTO COURS_EQUIPEMENT (id_cours, id_equipement, quantite_requise) VALUES
(1, 3, 15);