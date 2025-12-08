<?php
 ini_set('display_errors', 1);
error_reporting(E_ALL);
require __DIR__ . '/../../database/db.php';

// ATTENTION: Les requêtes suivantes sont CRITIQUEMENT VULNÉRABLES à l'injection SQL 
// car les variables GET et POST sont insérées directement sans préparation.
// Par contrainte, le PHP n'est pas modifié, mais des requêtes préparées sont VIVEMENT recommandées.

$id = $_GET['id'];

// Récupération des données existantes
$sql = "SELECT * FROM EQUIPEMENT WHERE id_equipement = $id";
$res = mysqli_query($conn, $sql);
$result = mysqli_fetch_assoc($res);

if (isset($_POST['submit'])) {
    
    $name = $_POST['name'];
    $type = $_POST['type'];
    $quantite = $_POST['quantite'];
    $etat = $_POST['etat'];
    
    // Requête de mise à jour vulnérable
    $sql_update = "UPDATE EQUIPEMENT
                   SET nom='$name', type_equipement='$type', quantite_disponible='$quantite' , etat='$etat'
                   WHERE id_equipement=$id";

    if (mysqli_query($conn, $sql_update)) { 
        header("Location: ../index_equipement.php");
        
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
    <title>Modifier l'Équipement</title>

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
             content: "✏️ Modifier l'Équipement";
        }
        .container h1 {
             font-size: 1.5rem;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        /* Utilisation de Flex pour aligner label et input/select sur la même ligne */
        form > br + br { 
            display: none;
        }
        form > br {
            display: none;
        }
        
        /* Cible les labels et les groupes input/select */
        form > :not(br):not(button) {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 15px;
            font-weight: 500;
            color: #555;
            flex-wrap: wrap; /* Permet au contenu de s'adapter si petit écran */
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
        
        /* Le select doit prendre toute la largeur pour le style */
        form select[name="etat"] {
            width: 60%;
        }

        form input:focus, form select:focus {
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
        
        type equipement : <input type="text" name="type" value="<?= htmlspecialchars($result['type_equipement']) ?>"><br><br>
        
        quantite : <input type="number" name="quantite" value="<?= htmlspecialchars($result['quantite_disponible']) ?>"><br><br>
        
        etat : 
        <select name="etat">
            <option value="bon" <?= ($result['etat'] == 'bon') ? 'selected' : '' ?>>bon</option>
            <option value="moyen" <?= ($result['etat'] == 'moyen') ? 'selected' : '' ?>>moyen</option>
            <option value="a remplacer" <?= ($result['etat'] == 'a remplacer') ? 'selected' : '' ?>>a remplacer</option>
        </select>
        <br><br>

        <button type="submit" name="submit">Modifier l'Équipement</button>
    </form>
    
    <a href="../index_equipement.php" class="back-link">Annuler et Retourner à la liste</a>
</div>

</body>
</html>