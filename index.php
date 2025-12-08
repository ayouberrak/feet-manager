<?php
require './database/db.php';

if($_SERVER['REQUEST_METHOD'] === "POST"){
    $email = $_POST['email'];
    $pass = $_POST['password'];
    $sql = "SELECT * FROM users WHERE email='$email' AND pass='$pass' ";
    $res = mysqli_query($conn, $sql);

    $response = ['success' => false, 'message' => 'Connexion échouée.'];

    if($res && mysqli_num_rows($res) > 0){
        session_start();
        $user = mysqli_fetch_assoc($res);
        $_SESSION['nom'] = $user['nom'];
        $response = [
            'success' => true, 
            'message' => 'Connexion réussie !', 
            'redirect' => 'home.php?conexionyes'
        ];
    }
    
    header('Content-Type: application/json');
    echo json_encode($response);
    exit; 
}

?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>

    <style>
        :root {
            --primary-color: #007bff; 
            --secondary-color: #6c757d; 
            --background-color: #f8f9fa; 
            --card-background: #ffffff; 
            --text-color: #343a40; 
            --border-color: #ced4da; 
            --success-color: #28a745;
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

        form input[type="email"]:focus,
        form input[type="password"]:focus {
            border-color: var(--primary-color);
            outline: none;
            box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.25);
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
            background-color: #0056b3; 
        }

        a {
            display: block;
            text-align: center;
            margin-top: 1rem;
            color: var(--secondary-color);
            text-decoration: none;
            transition: color 0.3s ease;
        }

        a:hover {
            color: var(--primary-color);
            text-decoration: underline;
        }

        .message-box {
            padding: 10px;
            margin-bottom: 1rem;
            border-radius: 4px;
            text-align: center;
            font-weight: 600;
        }
        .message-error {
            background-color: #f8d7da;
            color: var(--error-color);
            border: 1px solid #f5c6cb;
        }
        .message-success {
            background-color: #d4edda;
            color: var(--success-color);
            border: 1px solid #c3e6cb;
        }
    </style>

</head>
<body>
    <div class="container">
        <h1>Connexion</h1>
        
        <div id="message-container"></div> 

        <form id="loginForm" action="" method="POST">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" required>
            
            <label for="password">Mot de passe</label>
            <input type="password" id="password" name="password" required>
            
            <button type="submit">Se connecter</button>
            <a href="log/signup.php">S'inscrire</a>
        </form>
    </div>

    <script>
        document.getElementById('loginForm').addEventListener('submit', function(event) {
            event.preventDefault(); 

            const form = event.target;
            const messageContainer = document.getElementById('message-container');
            
            messageContainer.innerHTML = ''; 

            const formData = new FormData(form);

            fetch(form.action, {
                method: 'POST',
                body: formData
            })
            .then(response => {
                const contentType = response.headers.get("content-type");
                if (contentType && contentType.indexOf("application/json") !== -1) {
                    return response.json();
                } else {
                    throw new Error("Réponse inattendue du serveur.");
                }
            })
            .then(data => {
                if (data.success) {
                    messageContainer.innerHTML = `<div class="message-box message-success">${data.message}</div>`;
                    window.location.href = data.redirect;
                } else {
                    messageContainer.innerHTML = `<div class="message-box message-error">${data.message}</div>`;
                }
            })
            .catch(error => {
                console.error('Erreur de soumission:', error);
                messageContainer.innerHTML = `<div class="message-box message-error">Une erreur inattendue est survenue.</div>`;
            });
        });
    </script>
    </body>
</html>