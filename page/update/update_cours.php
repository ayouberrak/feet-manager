<?php
 ini_set('display_errors', 1);
error_reporting(E_ALL);
require __DIR__ . '/../../database/db.php';

// ATTENTION: Les requêtes suivantes sont CRITIQUEMENT VULNÉRABLES à l'injection SQL 
// car les variables GET et POST sont insérées directement sans préparation.
// Par contrainte, le PHP n'est pas modifié, mais des requêtes préparées sont VIVEMENT recommandées.

$id = $_GET['id'];

// Récupération des données existantes
$sql = "SELECT * FROM COURS WHERE id_cours = $id";
$res = mysqli_query($conn, $sql);
$result = mysqli_fetch_assoc($res);

if (isset($_POST['submit'])) {
    
    $name = $_POST['name'];
    $categorie = $_POST['categorie'];
    $date = $_POST['date'];
    $heure = $_POST['heures'];
    $durre = $_POST['duree'];
    $max_p = $_POST['max_p'];
    
    // Requête de mise à jour vulnérable
    $sql_update = "UPDATE COURS
                   SET nom='$name', categorie='$categorie', date_cours='$date' , heure_cours='$heure',duree='$durre',max_participants='$max_p'
                   WHERE id_cours=$id";

    if (mysqli_query($conn, $sql_update)) { 
        header("Location: ../index_cours.php");
        
        exit;
    } else {
        // Le message d'erreur sera affiché en haut de page
        echo "Erreur lors de la modification.";
    }
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier le Cours</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary-color: #007bff; /* Bleu pour l'action/Modification */
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
             content: "✏️ Modifier le Cours";
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
        form > :not(br):not(button):not(input[type="submit"]) {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 15px;
            font-weight: 500;
            color: #555;
        }
        
        form input[type="text"],
        form input[type="date"],
        form input[type="time"],
        form input[type="number"] {
            width: 60%;
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
            box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.25);
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
        Nom : <input type="text" name="name" value="<?= htmlspecialchars($result['nom']) ?>"><br><br>
        categorie : <input type="text" name="categorie" value="<?= htmlspecialchars($result['categorie']) ?>"><br><br>
        date : <input type="date" name="date" value="<?= htmlspecialchars($result['date_cours']) ?>"><br><br>
        heure : <input type="time" name="heures" value="<?= htmlspecialchars($result['heure_cours']) ?>"><br><br>
        durre : <input type="number" name="duree" value="<?= htmlspecialchars($result['duree']) ?>"><br><br>
        max participants : <input type="number" name="max_p" value="<?= htmlspecialchars($result['max_participants']) ?>"><br><br>

        <button type="submit" name="submit">Modifier le Cours</button>
    </form>
    
    <a href="../index_cours.php" class="back-link">Annuler et Retourner à la liste</a>
</div>

</body>
</html>