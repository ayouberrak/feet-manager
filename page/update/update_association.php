<?php
require __DIR__ .'/../../database/db.php';

$id_cours = $_GET['cours'];
$id_equipement = $_GET['equipement'];

$jointureUpdate = "SELECT c.id_cours, e.id_equipement, c.nom AS nom_cours, e.nom AS nom_equipement  
                    FROM COURS c 
                    INNER JOIN COURS_EQUIPEMENT a ON c.id_cours = a.id_cours 
                    INNER JOIN EQUIPEMENT e ON e.id_equipement = a.id_equipement
                    WHERE c.id_cours = $id_cours AND e.id_equipement = $id_equipement";
$joinResUpdate = mysqli_query($conn, $jointureUpdate);
$rest = mysqli_fetch_assoc($joinResUpdate);

$allEquip = "SELECT * FROM EQUIPEMENT";
$equipRes = mysqli_query($conn, $allEquip);


if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $id_newEquipement = $_POST['id_equipement_select'];

    $update = "UPDATE COURS_EQUIPEMENT 
               SET id_equipement = $id_newEquipement
               WHERE id_cours = $id_cours AND id_equipement = $id_equipement";

    if(mysqli_query($conn, $update)){
        echo "Modification validée";
    } else {
        echo "Erreur de modification";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier</title>
</head>
<body>

<form action="" method="POST">

    <select name="id_cours_select" disabled>
        <option value="<?= $rest['id_cours'] ?>">
            <?= $rest['nom_cours'] ?>
        </option>
    </select>

    <select name="id_equipement_select">
        <option value="<?= $rest['id_equipement'] ?>">
            <?= $rest['nom_equipement'] ?> (actuel)
        </option>

        <?php while($eq = mysqli_fetch_assoc($equipRes)){ ?>
            <?php if($eq['id_equipement'] != $rest['id_equipement']){ ?>
                <option value="<?= $eq['id_equipement'] ?>">
                    <?= $eq['nom'] ?>
                </option>
            <?php } ?>
        <?php } ?>
    </select>

    <button type="submit">Modifier</button>
</form>

</body>
</html>
