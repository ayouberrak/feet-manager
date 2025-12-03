<?php
require 'db.php';

    if($_SERVER["REQUEST_METHOD"] === "POST"){
        $name = $_POST["name"];
        $email = $_POST["email"];
        $password = $_POST["password"];

        $req_sql = "INSERT INTO user(non,email,mot_de_passe)
                    VALUES('$name','$email','$password')";
        if($dbC->query($req_sql)== TRUE){
            echo "add register";
            header("Location:conexion.php?signup=yes");
            exit;
        }else{
            echo 'erreur ';
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
    <h1>insecription</h1>
    <form action="" method="post">
    <label for="name">non</label>
    <input type="text" name="name"><br>
    <label for="email">email</label>
    <input type="text" name="email"><br>
    <label for="password">mot de passe</label>
    <input type="text" name="password" ><br>
    <button>s'inscrire</button>
    </form>
</body>
</html>
