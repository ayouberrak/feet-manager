<?php 
$user = 'root';
    $pass ='12121212';
    $servername = 'localhost';
    $dbname = 'cours_equipement';
    $dbC = new mysqli($servername , $user , $pass ,$dbname);
    if($dbC->connect_error){
        die("erreur de la bases de donnes : " . $dbC ->connect_error);
}
?>