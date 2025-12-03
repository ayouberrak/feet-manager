<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'db.php';

if($_SERVER["REQUEST_METHOD"] == "POST"){
        $non = isset($_POST['non']) ? $_POST['non'] : '';
    $categorie = isset($_POST['categorie']) ? $_POST['categorie'] : '';
    $datee = isset($_POST['date']) ? $_POST['date'] : '';
    $heures = isset($_POST['heures']) ? $_POST['heures'] : '';
    $durre = isset($_POST['durre']) ? $_POST['durre'] : '';
    $nbrM = isset($_POST['nbrM']) ? $_POST['nbrM'] : '';
        
        $Re_sql = "INSERT INTO cours(non_cours,categorie_cours,date_cours,heure_cours,durre_cours,nbr_max)
                    VALUES ('$non','$categorie','$datee','$heures','$durre','$nbrM')";

        if($dbC->query($Re_sql) === TRUE){
            header("Location:brief.php?ajouter_cours=yes");
            exit();
        }else{
        echo "echec error";
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css.css">
</head>
<body>
    <div class="div-principale">
        <h1>ajouter une cours</h1>
        <div class="div-form">
            <form action="" method="POST">
                <label for="non">non</label><br>
                <input type="text" name="non"><br>
                <label for="categorie">categorie</label><br>
                <input type="text" name="categorie"><br>
                <label for="date">date de cours</label><br>
                <input type="date" name="date"><br>
                <label for="heures">heures de cours</label><br>
                <input type="time" name="heures"><br>
                <label for="durre">durre de cours</label><br>
                <input type="nombre" name="durre"><br>
                <label for="nbrM">nombre de max de persone</label><br>
                <input type="nombre" name="nbrM"><br>
                <button>ajoute</button>
            </form>
        </div>
    </div>


    <?php 
        $requet = "SELECT * FROM cours";
        $res = $dbC->query($requet);
    ?>
        <?php 
            $requet = "SELECT * FROM cours";
                $res = $dbC->query($requet);

                echo "<form method='post'>";
                while ($row = mysqli_fetch_assoc($res)) {
                    echo "<div class='divv'>".
                    "<div>" .$row['categorie_cours'] ."</div>".
                    "<div>".$row['non_cours']."</div>".
                    "<button type='submit' name='edit' value=".$row['id_cours'].">modifier</button>".
                    "<button type='submit' name='supp' value =".$row['id_cours'].">supprimer</button>".
                    "</div>";
                }
                echo "</form>";

                if(isset($_POST['edit'])){
                    session_start();
                    $_SESSION['coursID'] = $_POST['edit'];
                    header('Location:edit.php');
                        exit();

                }
        ?>
        
</body>
</html>