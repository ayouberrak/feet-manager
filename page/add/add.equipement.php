<?php
 ini_set('display_errors', 1);
error_reporting(E_ALL);

require __DIR__ . '/../../database/db.php';

    if(isset($_POST['submit'])) {

        $name = $_POST['name'];
        $type = $_POST['type'];
        $quantite = $_POST['quantite'];
        $etat = $_POST['etat'];


        // ATTENTION: La ligne suivante est CRITIQUEMENT VULNÉRABLE à l'injection SQL. 
        // Par contrainte de l'utilisateur, elle N'EST PAS MODIFIÉE, mais devrait l'être en production.
        $sql = "    INSERT INTO EQUIPEMENT (nom, type_equipement, quantite_disponible,etat)
                    VALUES ('$name', '$type', '$quantite', '$etat')";

if(!empty($name) && !empty($type) && !empty($quantite) && !empty($etat)){

        $result = mysqli_query($conn, $sql);

        if($result) {
            header("Location: ../index_equipement.php");
            exit;
        }else {
            // Le message d'erreur sera affiché en haut de page
            echo "Error WHile inserting";
        }
    }else{
        // Le message d'erreur sera affiché en haut de page
        echo "tous les champs sont disponible";
    }
}


?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un Équipement</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary-color: #007bff; /* Bleu pour l'ajout/succès (cohérence avec la gestion des équipements) */
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
            color: var(--primary-color);
            margin-bottom: 1.5rem;
            font-weight: 600;
        }
        /* Correction du titre via CSS sans toucher au HTML */
        body > h1 {
            display: none;
        }
        .container h1::before {
             content: "⚙️ Ajouter un Équipement";
        }
        .container h1 {
             font-size: 1.5rem;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        /* Utilisation de Flex pour aligner label et input sur la même ligne (pour compenser le manque de structure HTML) */
        form > br + br { 
            display: none;
        }
        form > br {
            display: none;
        }
        
        /* Cible les labels (le texte avant l'input) */
        form > :not(br):not(button):not(input[type="submit"]):not(select) {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 15px;
            font-weight: 500;
            color: #555;
        }
        
        form input[type="text"],
        form input[type="number"],
        form select {
            width: 60%;
            padding: 10px;
            border: 1px solid var(--border-color);
            border-radius: 4px;
            box-sizing: border-box; 
            transition: border-color 0.3s, box-shadow 0.3s;
            font-family: inherit;
        }
        
        /* Pour le select qui est seul */
        form select[name="etat"] {
            width: 100%;
            margin-bottom: 1.25rem;
        }
        
        /* Adapter la ligne du select pour qu'elle ait un label */
        form > select + br {
            display: flex !important;
            justify-content: flex-start;
            align-items: center;
            margin-bottom: 15px;
            font-weight: 500;
            color: #555;
        }

        form input:focus, form select:focus {
            border-color: var(--primary-color);
            outline: none;
            box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.25); /* Ombre bleue */
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
            background-color: #0056b3; 
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
    <h1 style="text-align: center;"></h1>

    <form method="POST">
        Nom : <input type="text" name="name" required >
        <br><br>
        type : <input type="text" name="type" required >
        <br><br>
        quantite : <input type="number" name="quantite" required >
        <br><br>
        
        État : 
        <select name="etat">
            <option value="bon">Bon</option>
            <option value="moyen">Moyen</option>
            <option value="a remplacer">À Remplacer</option>
        </select>
        <br>
        
        <button type="submit" name="submit">Ajouter l'Équipement</button>
    </form>
    
    <a href="../index_equipement.php" class="back-link">Retour à la liste des équipements</a>
</div>

</body>
</html>