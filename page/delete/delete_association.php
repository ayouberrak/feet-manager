<?php
 ini_set('display_errors', 1);
error_reporting(E_ALL);
require __DIR__ . '/../../database/db.php';

if (isset($_GET['cours']) && isset($_GET['equipement'])) {
    $id_c = $_GET['cours'];
    $id_e = $_GET['equipement'];

    $sql = "DELETE FROM COURS_EQUIPEMENT WHERE id_cours = $id_c AND id_equipement =$id_e";

    if (mysqli_query($conn, $sql)) {
        header("Location: ../index_cours_equipement.php");
        exit;
    } else {
        echo "Erreur lors de la suppression.";
    }
}
?>