<?php 
    session_start();
    require '../database/db.php';
    
    if (!isset($_SESSION['nom'])) {
        header("Location: ../index.php"); 
        exit;
    }
    
    
    $req1 = 'SELECT * FROM COURS';
    $res = mysqli_query($conn,$req1);

    $req2 = 'SELECT * FROM EQUIPEMENT';
    $ress = mysqli_query($conn,$req2);

    if($_SERVER['REQUEST_METHOD'] ==='POST'){
        $id_cours = $_POST['cours_select'];
        $id_equipement = $_POST['equipement_select'];
        
        // ATTENTION: VULNÉRABILITÉ D'INJECTION SQL. 
        // L'implémentation doit utiliser les requêtes préparées pour la production.
        $resq="INSERT INTO COURS_EQUIPEMENT(id_cours,id_equipement)
                VALUES($id_cours,$id_equipement)";
                
        if(mysqli_query($conn,$resq)){
            $success_message = 'Association effectuée avec succès.';
        } else {
             // Utiliser mysqli_error() pour le débogage, mais pas dans l'interface utilisateur
             $error_message = 'Erreur lors de l\'association.'; 
        }
    }


    // --- Récupération des Associations ---
    // Ajout des IDs dans le SELECT pour les liens d'action
    $jointure = "SELECT c.id_cours, e.id_equipement, c.nom AS nom_cours, e.nom AS nom_equipement  FROM COURS c 
                INNER JOIN COURS_EQUIPEMENT a ON c.id_cours = a.id_cours 
                INNER JOIN EQUIPEMENT e ON e.id_equipement = a.id_equipement";
    $joinRes =mysqli_query($conn,$jointure); 

    
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Associer Cours & Équipements</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary-color: #007bff; 
            --success-color: #28a745; 
            --danger-color: #dc3545;
            --sidebar-width: 250px;
            --background-color: #f4f7f6; 
            --card-background: #ffffff; 
            --sidebar-background: #343a40; 
            --text-color: #343a40;
            --sidebar-text-color: #ffffff;
            --sidebar-hover-color: #495057;
            --dropdown-bg: #ffffff;
            --table-header-bg: #e9ecef;
            --table-border-color: #dee2e6;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--background-color);
            margin: 0;
            padding: 0;
            color: var(--text-color);
            display: flex;
            min-height: 100vh;
        }

        /* ------------------------- BARRE LATÉRALE (SIDEBAR) ------------------------- */
        .sidebar {
            width: var(--sidebar-width);
            background-color: var(--sidebar-background);
            color: var(--sidebar-text-color);
            padding: 20px 0;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.2); 
            display: flex;
            flex-direction: column;
        }
        
        .sidebar h2 {
            text-align: center;
            color: var(--primary-color);
            margin-bottom: 30px;
            font-size: 1.6rem;
            font-weight: 700;
            border-bottom: 1px solid rgba(255, 255, 255, 0.15); 
            padding-bottom: 15px;
            margin-left: 20px;
            margin-right: 20px;
        }

        .sidebar a {
            padding: 15px 20px;
            text-decoration: none;
            font-size: 1rem;
            font-weight: 400;
            color: rgba(255, 255, 255, 0.85);
            display: block;
            transition: background-color 0.4s ease-out, border-left 0.4s ease-out;
            border-left: 5px solid transparent;
        }

        .sidebar a:hover, .sidebar a.active {
            background-color: var(--sidebar-hover-color);
            color: var(--sidebar-text-color);
            border-left-color: var(--primary-color);
            box-shadow: inset 3px 0 5px rgba(0, 0, 0, 0.3); 
        }

        /* --- Contenu Principal --- */
        .main-container {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }
        
        /* ------------------------- EN-TÊTE ET MENU DE PROFIL ------------------------- */
        .top-header {
            background-color: var(--card-background);
            padding: 1rem 2rem;
            display: flex;
            justify-content: flex-end; 
            align-items: center;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08); 
            border-bottom: 1px solid #e0e0e0;
        }

        .profile-dropdown {
            position: relative;
            display: inline-block;
        }

        .profile-button {
            display: flex;
            align-items: center;
            background: none;
            border: none;
            cursor: pointer;
            padding: 8px 15px;
            border-radius: 25px;
            transition: background-color 0.3s;
        }

        .profile-button:hover, .profile-button.active {
            background-color: var(--background-color);
        }

        .profile-icon {
            width: 30px;
            height: 30px;
            background-color: var(--primary-color);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            margin-right: 10px;
            font-size: 1.1rem;
        }

        .profile-name {
            font-weight: 600;
            color: var(--text-color);
            margin-right: 10px;
        }
        
        .arrow-icon {
            transition: transform 0.3s;
        }

        .profile-button.active .arrow-icon {
            transform: rotate(180deg);
        }

        /* Contenu du menu déroulant */
        .dropdown-content {
            display: none;
            position: absolute;
            right: 0;
            background-color: var(--dropdown-bg);
            min-width: 200px;
            box-shadow: 0 8px 16px 0 rgba(0,0,0,0.2);
            z-index: 1000;
            border-radius: 8px;
            border: 1px solid var(--table-border-color);
            margin-top: 5px;
            opacity: 0;
            transform: translateY(-10px);
            transition: opacity 0.3s ease-out, transform 0.3s ease-out;
        }

        .dropdown-content.show {
            display: block;
            opacity: 1;
            transform: translateY(0);
        }
        
        .dropdown-header {
            padding: 10px 15px;
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--primary-color);
            border-bottom: 1px solid var(--table-border-color);
        }

        .dropdown-content a {
            color: var(--text-color);
            padding: 12px 15px;
            text-decoration: none;
            display: block;
            font-size: 0.95rem;
            transition: background-color 0.3s;
        }

        .dropdown-content a:hover {
            background-color: var(--background-color);
        }

        .dropdown-content .logout-link {
            border-top: 1px solid var(--table-border-color);
            color: var(--danger-color);
            font-weight: 600;
        }

        .dropdown-content .logout-link:hover {
            background-color: #f8d7da; 
        }

        /* ------------------------- CONTENU DE LA PAGE ------------------------- */
        .main-content {
            padding: 2.5rem 2rem; 
            flex-grow: 1;
        }
        
        .content-card {
            background-color: var(--card-background);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }
        
        h1 {
            color: var(--primary-color);
            margin-top: 0;
            font-weight: 600;
            text-align: center;
            border-bottom: 2px solid var(--primary-color);
            display: inline-block;
            padding-bottom: 5px;
            margin-bottom: 25px;
            width: fit-content;
            margin-left: auto;
            margin-right: auto;
        }
        
        /* --- Messages (Succès/Erreur) --- */
        .message {
            padding: 10px 15px;
            margin-bottom: 20px;
            border-radius: 5px;
            font-weight: 500;
            text-align: center;
        }

        .message.success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        /* --- Formulaire d'Association --- */
        .association-form {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 15px;
            margin-bottom: 30px;
            padding: 20px;
            border: 1px dashed var(--table-border-color);
            border-radius: 8px;
            background-color: #fcfcfc;
        }

        .association-form select {
            padding: 10px 15px;
            border-radius: 5px;
            border: 1px solid var(--table-border-color);
            font-family: 'Poppins', sans-serif;
            min-width: 200px;
            transition: border-color 0.3s;
        }

        .association-form select:focus {
            border-color: var(--primary-color);
            outline: none;
        }

        .association-form button[type="submit"] {
            padding: 10px 20px;
            background-color: var(--primary-color);
            color: white;
            border: none;
            border-radius: 5px;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .association-form button[type="submit"]:hover {
            background-color: #0056b3;
        }
        
        /* --- Tableau des Associations --- */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            border: 1px solid var(--table-border-color);
            border-radius: 8px;
            overflow: hidden; 
        }

        .data-table th, 
        .data-table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid var(--table-border-color);
        }

        .data-table th {
            background-color: var(--table-header-bg);
            color: var(--text-color);
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.9rem;
        }

        .data-table tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .data-table tr:hover:not(:first-child) {
            background-color: #e9e9e9;
            transition: background-color 0.2s;
        }
        
        /* --- Styles des Liens d'Action (comme dans index_cours/equipement) --- */
        .action-link {
            text-decoration: none;
            margin-right: 10px;
            font-weight: 500;
            transition: color 0.2s;
            white-space: nowrap;
        }

        .action-link.edit {
            color: var(--primary-color);
        }

        .action-link.edit:hover {
            color: #0056b3;
        }

        .action-link.delete {
            color: var(--danger-color);
        }

        .action-link.delete:hover {
            color: #c82333;
        }
        
        .list-header {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--text-color);
            margin-bottom: 15px;
            padding-left: 10px;
        }
        
    </style>
</head>
<body>

    <div class="sidebar">
        <h2>Dashboard</h2>
        <nav>
            <a href="../home.php">🏠 Tableau de Bord</a> 
            <a href="index_cours.php">📚 Gérer les Cours</a>
            <a href="index_equipement.php">⚙️ Gérer les Équipements</a>
            <a href="association.php" class="active">🔗 Associations</a>
        </nav>
    </div>

    <div class="main-container">
        
        <div class="top-header">
            
            <div class="profile-dropdown" id="profileDropdown">
                <button class="profile-button" onclick="toggleDropdown()">
                    <span class="profile-icon"><?= strtoupper(substr($_SESSION['nom'] ?? 'U', 0, 1)) ?></span> 
                    <span class="profile-name"><?= htmlspecialchars($_SESSION['nom'] ?? 'Profil') ?></span>
                    <span class="arrow-icon">▼</span>
                </button>
                
                <div class="dropdown-content" id="dropdownContent">
                    <div class="dropdown-header">Connecté en tant que <?= htmlspecialchars($_SESSION['nom'] ?? 'Utilisateur') ?></div>
                    
                    <a href="#profile">👤 Mon Profil</a>
                    <a href="index_equipement.php">⚙️ Mes Équipements</a>
                    <a href="index_cours.php">📚 Mes Cours</a>
                    
                    <a href="#" class="logout-link" onclick="document.getElementById('logoutForm').submit(); return false;">
                        ➡️ Déconnexion
                    </a>
                </div>
            </div>
            
            <form id="logoutForm" action="../logout.php" method="POST" style="display: none;"></form>
            
        </div>

        <div class="main-content">
            <div class="content-card">
                
                <h1>🔗 Gérer les Associations Cours - Équipement</h1>

                <?php 
                    // Affichage du message de succès
                    if (isset($success_message)) {
                        echo '<div class="message success">' . htmlspecialchars($success_message) . '</div>';
                    }
                    // Affichage du message d'erreur
                    if (isset($error_message)) {
                         echo '<div class="message error" style="background-color: #f8d7da; color: var(--danger-color); border: 1px solid #f5c6cb;">' . htmlspecialchars($error_message) . '</div>';
                    }
                ?>

                <form action="" method="POST" class="association-form">
                    <label for="cours_select">Cours :</label>
                    <select name="cours_select" id="cours_select">
                        <?php 
                             if (mysqli_num_rows($res) > 0) {
                                 mysqli_data_seek($res, 0); 
                             }
                            while($row = mysqli_fetch_assoc($res)){ ?>
                                <option value="<?= $row['id_cours']?>"><?= htmlspecialchars($row['nom'])?></option>
                        <?php
                            }
                        ?>
                    </select>
                    
                    <label for="equipement_select">Équipement :</label>
                    <select name="equipement_select" id="equipement_select">
                        <?php 
                            if (mysqli_num_rows($ress) > 0) {
                                 mysqli_data_seek($ress, 0); 
                            }
                            while($row2 = mysqli_fetch_assoc($ress)){ ?>
                                <option value="<?= $row2['id_equipement']?>"><?= htmlspecialchars($row2['nom'])?></option>
                        <?php
                            }
                        ?>
                    </select>
                    <button type="submit">Associer</button>
                </form>
                
                <h2 class="list-header">Associations Actuelles</h2>
                
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Nom du Cours</th>
                            <th>Nom de l'Équipement</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php 
                        if (mysqli_num_rows($joinRes) > 0) {
                            while($row3 = mysqli_fetch_assoc($joinRes)){ ?>
                            <tr>
                                <td><?= htmlspecialchars($row3['nom_cours'])?></td>
                                <td><?= htmlspecialchars($row3['nom_equipement'])?></td>
                                
                                <td>
                                    <a href="delete/delete_association.php?cours=<?= $row3['id_cours'] ?>&equipement=<?= $row3['id_equipement'] ?>" class="action-link delete" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette association ?')">🗑️ Supprimer</a>
                                    <span style="color:#ccc; margin-right:5px;">|</span>
                                    <a href="update/update_association.php?cours=<?= $row3['id_cours'] ?>&equipement=<?= $row3['id_equipement'] ?>" class="action-link edit">✏️ Modifier</a>
                                </td>
                            </tr>
                        <?php
                            }
                        } else { ?>
                            <tr>
                                <td colspan="3" style="text-align: center; color: #6c757d; padding: 20px;">
                                    Aucune association trouvée.
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        function toggleDropdown() {
            const content = document.getElementById("dropdownContent");
            const button = document.querySelector(".profile-button");
            
            content.classList.toggle("show");
            button.classList.toggle("active");
        }

        window.onclick = function(event) {
            if (!event.target.closest('.profile-dropdown')) {
                const dropdowns = document.getElementsByClassName("dropdown-content");
                const buttons = document.getElementsByClassName("profile-button");
                
                for (let i = 0; i < dropdowns.length; i++) {
                    if (dropdowns[i].classList.contains('show')) {
                        dropdowns[i].classList.remove('show');
                        if (buttons[i]) buttons[i].classList.remove('active');
                    }
                }
            }
        }
    </script>
</body>
</html>