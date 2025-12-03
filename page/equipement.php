<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

require '../database/db.php';

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $non = $_POST['non'];
    $type = $_POST['type'];
    $quantity_equi =  $_POST['quantity_equi'];
    $etat_equi =$_POST['etat_equi'];

        
        $Re_sql = "INSERT INTO equipement(non_equi,type_equi,quantity_equi,etat_equi)
                    VALUES ('$non','$type','$quantity_equi','$etat_equi')";

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
</head>
<body>
    <div class="div-principale">
        <h1>ajouter une cours</h1>
        <div class="div-form">
            <form action="" method="POST">
                <label for="non">non</label><br>
                <input type="text" name="non"><br>
                <label for="type">type</label><br>
                <input type="text" name="type"><br>
                <label for="date">quantity_equi</label><br>
                <input type="quantity_equi" name="quantity_equi"><br>
                <label for="etat_equi">etat_equi</label><br>
                <input type="text" name="etat_equi"><br>
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