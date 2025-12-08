
<?php
 ini_set('display_errors', 1);
error_reporting(E_ALL);
require __DIR__ . '/../../database/db.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "DELETE FROM EQUIPEMENT WHERE id_equipement = $id";

    if (mysqli_query($conn, $sql)) {
        header("Location: ../index_equipement.php");
        exit;
    } else {
        echo "Erreur lors de la suppression.";
    }
}
?>