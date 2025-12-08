<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require '../database/db.php';

    if($_SERVER["REQUEST_METHOD"] === "POST"){
        $name = $_POST["name"];
        $email = $_POST["email"];
        $password = $_POST["password"];

        $req_sql = "INSERT INTO users(nom,email,pass)
                    VALUES('$name','$email','$password')";
        if($conn->query($req_sql)== TRUE){
            header("Location: ../index.php?signup=yes");
            exit;
        }else{
            echo 'erreur ';
        }
    }
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>

    <style>
        :root {
            --primary-color: #007bff; /* Vert pour l'inscription (thème) */
            --secondary-color: #6c757d; 
            --background-color: #f8f9fa; 
            --card-background: #ffffff; 
            --text-color: #343a40; 
            --border-color: #ced4da; 
            --error-color: #dc3545;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            background-color: var(--background-color);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            color: var(--text-color);
        }

        .container {
            width: 100%;
            max-width: 400px;
            padding: 2rem;
            background-color: var(--card-background);
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: var(--primary-color);
            margin-bottom: 1.5rem;
        }

        form label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
        }

        form input[type="text"],
        form input[type="email"],
        form input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 1.25rem;
            border: 1px solid var(--border-color);
            border-radius: 4px;
            box-sizing: border-box; 
            transition: border-color 0.3s;
        }

        form input:focus {
            border-color: var(--primary-color);
            outline: none;
            /* Utilisation d'une ombre verte pour le focus */
            box-shadow: 0 0 0 3px rgba(40, 167, 69, 0.25); 
        }

        button[type="submit"] {
            width: 100%;
            padding: 12px;
            background-color: var(--primary-color);
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1rem;
            font-weight: 600;
            transition: background-color 0.3s ease;
            margin-top: 0.5rem;
        }

        button[type="submit"]:hover {
            background-color: #1e7e34; /* Vert plus foncé au survol */
        }

        .login-link {
            display: block;
            text-align: center;
            margin-top: 1rem;
            color: var(--secondary-color);
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .login-link:hover {
            color: var(--primary-color);
            text-decoration: underline;
        }

        /* Style pour le message d'erreur si l'inscription échoue */
        .error-message {
            background-color: #f8d7da;
            color: var(--error-color);
            padding: 10px;
            margin-bottom: 1rem;
            border: 1px solid #f5c6cb;
            border-radius: 4px;
            text-align: center;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Inscription</h1>
        
        <?php 
        // Affichage du message d'erreur si la soumission a échoué (basé sur l'écho 'erreur ')
        if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === "POST" && strpos(ob_get_contents(), 'erreur ') !== false) {
             // Utilisation d'ob_get_contents() pour capturer la sortie avant l'éventuelle redirection
            // C'est une méthode de contournement pour le cas où l'erreur n'est pas gérée dans l'en-tête
            echo '<div class="error-message">Erreur lors de l\'inscription. Veuillez vérifier les informations.</div>';
        } else if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === "POST" && strpos(ob_get_contents(), 'add register') !== false) {
             // Affichage d'un message de succès si l'ajout est réussi mais que la redirection n'a pas fonctionné
             echo '<div class="error-message" style="background-color: #d4edda; color: #155724; border-color: #c3e6cb;">Inscription réussie ! Redirection en cours...</div>';
        }
        ?>

        <form action="" method="post">
            <label for="name">Nom</label>
            <input type="text" id="name" name="name" required><br>
            
            <label for="email">Email</label>
            <input type="email" id="email" name="email" required><br>
            
            <label for="password">Mot de passe</label>
            <input type="password" id="password" name="password" required><br>
            
            <button type="submit">S'inscrire</button>
            <a href="../index.php" class="login-link">Déjà un compte ? Connectez-vous</a>
        </form>
    </div>
</body>
</html>