
<?php 
require '../database/db.php';
if($_SERVER["REQUEST_METHOD"] === "POST"){
    $email = $_POST["email"];
    $passs = $_POST["password"];
    if($passs != '' && $email!=''){
        $reqq=$dbC->query("SELECT * FROM user WHERE email = '$email' AND mot_de_passe = '$passs'");
        $rep = $reqq->fetch_assoc();
        if($rep){
            header("Location:brief.php?conexion:yes");
            exit;
        }else{
            echo"erreur";
        }
    }else{
        echo 'enter les champs';
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
    <h1>conexion</h1>
    <form action="" method="POST">
        <label for="email">email</label>
        <input type="text" name="email"><br>
        <label for="password">mot de passe</label>
        <input type="text" name="password" ><br>
        <button>s'inscrire</button>
    </form>
</body>
</html>
