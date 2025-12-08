
<?php
 ini_set('display_errors', 1);
error_reporting(E_ALL);
require __DIR__ . '/../../database/db.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "DELETE FROM COURS WHERE id_cours = $id";

    if (mysqli_query($conn, $sql)) {
        header("Location: ../index_cours.php");
        exit;
    } else {
        echo "Erreur lors de la suppression.";
    }
}
?>