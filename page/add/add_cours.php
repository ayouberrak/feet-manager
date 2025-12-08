<?php
 ini_set('display_errors', 1);
error_reporting(E_ALL);

require __DIR__ . '/../../database/db.php';

    if(isset($_POST['submit'])) {

        $name = $_POST['name'];
        $categorie = $_POST['categorie'];
        $date = $_POST['date'];
        $heure = $_POST['heures'];
        $durre = $_POST['duree'];
        $max_p = $_POST['max_p'];

        // ATTENTION: La ligne suivante est CRITIQUEMENT VULNÉRABLE à l'injection SQL. 
        // Par contrainte de l'utilisateur, elle N'EST PAS MODIFIÉE, mais devrait l'être en production.
        $sql = "INSERT INTO COURS (nom, categorie, date_cours,heure_cours,duree,max_participants)
                    VALUES ('$name', '$categorie', '$date', '$heure', '$durre', '$max_p')";

        if(!empty($name) && !empty($categorie) && !empty($date) && !empty($heure) && !empty($durre) && !empty($max_p)){

            $result = mysqli_query($conn, $sql);

            if($result) {
                header("Location: ../index_cours.php");
                exit;
            }else {
                // Le message d'erreur sera affiché en haut de page car le PHP echo directement
                echo "Error WHile inserting";
            }
        }else{
            // Le message d'erreur sera affiché en haut de page car le PHP echo directement
            echo "les champs sont obligatoire ";
        }
    }

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un Cours</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary-color: #007bff; /* Vert pour l'ajout/succès */
            --danger-color: #dc3545; 
            --background-color: #f8f9fa; 
            --card-background: #ffffff; 
            --text-color: #343a40; 
            --border-color: #ced4da; 
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--background-color);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            color: var(--text-color);
        }

        .container {
            width: 100%;
            max-width: 500px;
            padding: 2rem;
            background-color: var(--card-background);
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        /* Style pour les messages PHP existants qui sont echo */
        body > div:first-child:not(.container) {
            position: absolute;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 9999;
            background-color: #f8d7da;
            color: var(--danger-color);
            padding: 10px 20px;
            border: 1px solid #f5c6cb;
            border-radius: 4px;
            font-weight: 600;
        }

        h1 {
            text-align: center;
            /* Le titre original est "Ajouter un utilisateur", je le corrige ici via CSS */
            color: var(--primary-color);
            margin-bottom: 1.5rem;
            font-weight: 600;
        }

        /* Hack pour corriger le titre sans toucher au HTML d'origine */
        body > h1 {
            display: none; /* Cache le titre HTML original */
        }
        .container h1::before {
             content: "➕ Ajouter un Nouveau Cours"; /* Nouveau titre stylisé */
        }
        .container h1 {
             font-size: 1.5rem; /* Réapplique la taille du titre */
        }


        form {
            display: flex;
            flex-direction: column;
        }

        form label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: #555;
            /* Centrage du texte pour les labels (pour compenser les <br><br> de l'original) */
            text-align: left; 
        }

        /* Utilisation de Flex pour aligner label et input sur la même ligne (pour compenser le manque de structure HTML) */
        form > br + br { 
            display: none; /* Cacher les <br><br> */
        }
        form > br {
            display: none; /* Cacher les <br> */
        }
        
        form > :not(br):not(button):not(input[type="submit"]) {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 15px;
        }
        
        form input[type="text"],
        form input[type="date"],
        form input[type="time"],
        form input[type="number"] {
            width: 60%; /* Réduire la largeur des inputs */
            padding: 10px;
            border: 1px solid var(--border-color);
            border-radius: 4px;
            box-sizing: border-box; 
            transition: border-color 0.3s, box-shadow 0.3s;
            font-family: inherit;
        }
        

        form input:focus {
            border-color: var(--primary-color);
            outline: none;
            box-shadow: 0 0 0 3px rgba(40, 167, 69, 0.25); 
        }

        button[type="submit"] {
            width: 100%;
            padding: 12px;
            background-color: var(--primary-color);
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1rem;
            font-weight: 600;
            transition: background-color 0.3s ease, transform 0.2s ease;
            margin-top: 1rem;
        }

        button[type="submit"]:hover {
            background-color: #1e7e34; 
            transform: translateY(-1px);
        }

        /* Lien de retour */
        .back-link {
            display: block;
            text-align: center;
            margin-top: 1.5rem;
            color: #6c757d;
            text-decoration: none;
            transition: color 0.3s ease;
            font-weight: 500;
        }

        .back-link:hover {
            color: var(--primary-color);
        }

    </style>
</head>
<body>
<div class="container">
    <h1 style="text-align: center;"></h1> <form method="POST">
        Nom : <input type="text" name="name" required >
        <br><br>
        categorie : <input type="text" name="categorie" required >
        <br><br>
        date : <input type="date" name="date" required >
        <br><br>
        heure : <input type="time" name="heures" required >
        <br><br>
        durre : <input type="number" name="duree" required >
        <br><br>
        max participants : <input type="number" name="max_p" required >
        <br><br>

        <button type="submit" name="submit">Ajouter le Cours</button>
    </form>
    
    <a href="../index_cours.php" class="back-link">Retour à la liste des cours</a>
</div>

</body>
</html>