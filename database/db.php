<?php 
$user = 'root';
    $pass ='12121212';
    $servername = 'localhost';
    $dbname = 'feet_managerr';
    $conn = new mysqli($servername , $user , $pass ,$dbname);
    if($conn->connect_error){
        die("erreur de la bases de donnes : " . $conn ->connect_error);
}
?>