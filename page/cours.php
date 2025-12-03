<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require '../database/db.php';

if($_SERVER["REQUEST_METHOD"] == "POST"){
    // sécurisation des valeurs
    $non = mysqli_real_escape_string($dbC, $_POST['non']);
    $categorie = mysqli_real_escape_string($dbC, $_POST['categorie']);
    $datee = $_POST['date'];
    $heures = $_POST['heures'];
    $durre = (int)$_POST['durre'];
    $nbrM = (int)$_POST['nbrM'];

    $Re_sql = "INSERT INTO cours(non_cours,categorie_cours,date_cours,heure_cours,durre_cours,nbr_max)
               VALUES ('$non','$categorie','$datee','$heures','$durre','$nbrM')";

    if($dbC->query($Re_sql) === TRUE){
header("Location: /feet-manager/page/cours.php");
        exit();
    }else{
        echo "Échec de l'ajout : ".$dbC->error;
    }
}

// suppression d’un cours
if(isset($_POST['supp'])){
    $id = (int)$_POST['supp'];
    $dbC->query("DELETE FROM cours WHERE id_cours = $id");
    header("Location: cours.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Ajouter un cours</title>
<!-- <link rel="stylesheet" href="style/css.css"> -->
</head>
<body>

<div class="div-principale">
    <h1>Ajouter un cours</h1>
    <div class="div-form">
        <form action="cours.php" method="POST">
            <label for="non">Nom :</label><br>
            <input type="text" name="non" required><br>
            <label for="categorie">Catégorie :</label><br>
            <input type="text" name="categorie" required><br>
            <label for="date">Date :</label><br>
            <input type="date" name="date" required><br>
            <label for="heures">Heure :</label><br>
            <input type="time" name="heures" required><br>
            <label for="durre">Durée :</label><br>
            <input type="number" name="durre" required><br>
            <label for="nbrM">Nombre maximum :</label><br>
            <input type="number" name="nbrM" required><br>
            <button type="submit">Ajouter</button>
        </form>
    </div>
</div>

<div class="liste-cours">
<?php
$res = $dbC->query("SELECT * FROM cours");
while($row = mysqli_fetch_assoc($res)){
    echo "<div class='divv'>";
    echo "<div>".$row['categorie_cours']."</div>";
    echo "<div>".$row['non_cours']."</div>";
    echo "<form method='post' style='display:inline;'>
            <button type='submit' name='supp' value='".$row['id_cours']."'>Supprimer</button>
          </form>";
    echo "</div>";
}
?>
</div>

</body>
</html>
