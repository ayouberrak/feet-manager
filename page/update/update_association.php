<?php 
    // Démarrage de la session et connexion à la base de données
    session_start();
    require __DIR__ .'/../../database/db.php';
    
    // Redirection si l'utilisateur n'est pas connecté
    if (!isset($_SESSION['nom'])) {
        header("Location: ../../index.php"); 
        exit;
    }

    // Récupération des IDs passés par l'URL
    $id_cours = $_GET['cours'] ?? null;
    $id_equipement = $_GET['equipement'] ?? null;

    // Vérification de la présence des IDs
    if (!$id_cours || !$id_equipement) {
        $error_message = "IDs de cours ou d'équipement manquants.";
        // Optionnel: Rediriger vers la page des associations si IDs manquants
        // header("Location: association.php");
        // exit;
    }
    
    // --- 1. Récupération de l'association actuelle ---
    
    // ATTENTION: Les IDs sont ici utilisés directement dans la requête (vulnérable à l'injection SQL)
    $jointureUpdate = "SELECT c.id_cours, e.id_equipement, c.nom AS nom_cours, e.nom AS nom_equipement  
                        FROM COURS c 
                        INNER JOIN COURS_EQUIPEMENT a ON c.id_cours = a.id_cours 
                        INNER JOIN EQUIPEMENT e ON e.id_equipement = a.id_equipement
                        WHERE c.id_cours = $id_cours AND e.id_equipement = $id_equipement";
    $joinResUpdate = mysqli_query($conn, $jointureUpdate);
    $rest = mysqli_fetch_assoc($joinResUpdate);

    // --- 2. Récupération de tous les équipements disponibles ---
    $allEquip = "SELECT * FROM EQUIPEMENT";
    $equipRes = mysqli_query($conn, $allEquip);

    $success_message = null;
    $error_message = null;

    // --- 3. Traitement de la soumission du formulaire (UPDATE) ---
    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        $id_newEquipement = $_POST['id_equipement_select'];

        // ATTENTION: Requête non sécurisée
        $update = "UPDATE COURS_EQUIPEMENT 
                   SET id_equipement = $id_newEquipement
                   WHERE id_cours = $id_cours AND id_equipement = $id_equipement";

        if(mysqli_query($conn, $update)){
            // $success_message = "Modification validée. Redirection...";
            // Redirection après succès
            header("Location: ../index_cours_equipement.php?success=update");
            exit;
        } else {
            $error_message = "Erreur de modification: " . mysqli_error($conn);
        }
    }
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Association</title>

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
        /* Styles Profile Dropdown (Omis pour la concision ici, mais supposés exister) */
        
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

        .dropdown-content .logout-link {
            border-top: 1px solid var(--table-border-color);
            color: var(--danger-color);
            font-weight: 600;
        }

        /* ------------------------- FORMULAIRE DE MODIFICATION ------------------------- */
        .main-content {
            padding: 2.5rem 2rem; 
            flex-grow: 1;
        }
        
        .content-card {
            background-color: var(--card-background);
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            max-width: 600px; /* Limite la largeur du formulaire */
            margin: 0 auto;
        }
        
        .content-card h1 {
            color: var(--primary-color);
            margin-top: 0;
            font-weight: 600;
            border-bottom: 2px solid var(--primary-color);
            display: inline-block;
            padding-bottom: 5px;
            margin-bottom: 30px;
        }
        
        .update-form {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }
        
        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-group label {
            font-weight: 500;
            margin-bottom: 8px;
            color: var(--text-color);
        }

        .update-form select {
            padding: 10px 15px;
            border-radius: 5px;
            border: 1px solid var(--table-border-color);
            font-family: 'Poppins', sans-serif;
            font-size: 1rem;
            background-color: white;
            transition: border-color 0.3s;
        }
        
        .update-form select:focus {
            border-color: var(--primary-color);
            outline: none;
        }
        
        /* Style pour le champ désactivé */
        .update-form select[disabled] {
            background-color: #e9ecef;
            color: #6c757d;
            cursor: not-allowed;
        }

        .submit-button {
            padding: 12px 25px;
            margin-top: 10px;
            background-color: var(--primary-color);
            color: white;
            border: none;
            border-radius: 5px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.2s;
        }

        .submit-button:hover {
            background-color: #0056b3;
            transform: translateY(-2px);
        }

        /* --- Messages --- */
        .message {
            padding: 15px;
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

        .message.error {
            background-color: #f8d7da;
            color: var(--danger-color);
            border: 1px solid #f5c6cb;
        }
    </style>
</head>
<body>

    <div class="sidebar">
        <h2>Dashboard</h2>
        <nav>
            <a href="../../home.php">🏠 Tableau de Bord</a> 
            <a href="../index_cours.php">📚 Gérer les Cours</a>
            <a href="../index_equipement.php">⚙️ Gérer les Équipements</a>
            <a href="../index_cours_equipement.php" class="active">🔗 Associations</a>
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
                    <a href="../index_equipement.php">⚙️ Mes Équipements</a>
                    <a href="../index_cours.php">📚 Mes Cours</a>
                    
                    <a href="#" class="logout-link" onclick="document.getElementById('logoutForm').submit(); return false;">
                        ➡️ Déconnexion
                    </a>
                </div>
            </div>
            
            <form id="logoutForm" action="../../logout.php" method="POST" style="display: none;"></form>
            
        </div>

        <div class="main-content">
            <div class="content-card">
                
                <h1>✏️ Modifier l'Association</h1>

                <?php 
                    if (isset($success_message)) {
                        echo '<div class="message success">' . htmlspecialchars($success_message) . '</div>';
                    }
                    if (isset($error_message)) {
                         echo '<div class="message error">' . htmlspecialchars($error_message) . '</div>';
                    }
                    
                    // S'assurer que les données de l'association existent
                    if (!$rest): 
                ?>
                    <div class="message error">Association introuvable. Veuillez vérifier les IDs.</div>
                <?php else: ?>

                <form action="" method="POST" class="update-form">
                    
                    <div class="form-group">
                        <label for="id_cours_select">Cours Associé (Non modifiable)</label>
                        <select name="id_cours_select" id="id_cours_select" disabled>
                            <option value="<?= htmlspecialchars($rest['id_cours']) ?>">
                                <?= htmlspecialchars($rest['nom_cours']) ?>
                            </option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="id_equipement_select">Équipement</label>
                        <select name="id_equipement_select" id="id_equipement_select" required>
                            
                            <option value="<?= htmlspecialchars($rest['id_equipement']) ?>" selected>
                                <?= htmlspecialchars($rest['nom_equipement']) ?> (actuel)
                            </option>

                            <?php 
                                // Reset le pointeur de résultat si nécessaire
                                if (mysqli_num_rows($equipRes) > 0) {
                                    mysqli_data_seek($equipRes, 0); 
                                }
                                while($eq = mysqli_fetch_assoc($equipRes)){ 
                                    // Afficher uniquement les équipements différents de l'actuel
                                    if($eq['id_equipement'] != $rest['id_equipement']){ 
                            ?>
                                <option value="<?= htmlspecialchars($eq['id_equipement']) ?>">
                                    <?= htmlspecialchars($eq['nom']) ?>
                                </option>
                            <?php 
                                    } 
                                } 
                            ?>
                        </select>
                    </div>

                    <button type="submit" class="submit-button">Modifier l'Équipement Associé</button>
                </form>
                
                <?php endif; /* Fin du bloc if(!$rest) */ ?>

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