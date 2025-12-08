<?php
// Assurez-vous que cette page a accès à la session pour afficher le nom de l'utilisateur
session_start();
require '../database/db.php';

// Redirection si l'utilisateur n'est pas connecté
if (!isset($_SESSION['nom'])) {
    // Si la page est dans /page/, index.php est à la racine (../index.php)
    header("Location: ../index.php"); 
    exit;
}

$filtre = isset($_GET['filtre']) ? $_GET['filtre'] : 'all';

if ($filtre == "all") {
    // NOTE: Pour éviter l'injection SQL sur la variable $filtre, il est essentiel
    // d'utiliser les requêtes préparées.
    $sql = "SELECT * FROM EQUIPEMENT";
} else {
    // Utilisation de mysqli_real_escape_string pour une protection MINIMALE du filtre
    $filtre = mysqli_real_escape_string($conn, $filtre);
    $sql = "SELECT * FROM EQUIPEMENT WHERE type_equipement = '$filtre'";
}
$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Équipements</title>
    
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

        /* ------------------------- CONTENU DE LA PAGE (EQUIPEMENT) ------------------------- */
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
        
        .content-card h1 {
             color: var(--primary-color);
             margin-top: 0;
             font-weight: 600;
             border-bottom: 2px solid var(--primary-color);
             display: inline-block;
             padding-bottom: 5px;
             margin-bottom: 25px;
        }

        /* --- Bouton Ajouter --- */
        .add-button {
            display: inline-block;
            padding: 10px 20px;
            margin-bottom: 20px;
            background-color: var(--success-color);
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: 500;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .add-button:hover {
            background-color: #1e7e34;
            transform: translateY(-2px);
        }
        
        /* --- Formulaire de Filtre --- */
        .filter-form {
            display: flex;
            gap: 10px;
            align-items: center;
            margin-bottom: 20px;
            padding: 10px 0;
        }

        .filter-form label {
            font-weight: 500;
            color: #6c757d;
        }
        
        .filter-form select {
            min-width: 150px;
        }

        .filter-form select,
        .filter-form button {
            padding: 8px 15px;
            border-radius: 5px;
            border: 1px solid var(--table-border-color);
            font-family: 'Poppins', sans-serif;
            transition: border-color 0.3s;
        }
        
        .filter-form select:focus {
            border-color: var(--primary-color);
            outline: none;
        }

        .filter-form button {
            background-color: var(--primary-color);
            color: white;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .filter-form button:hover {
            background-color: #0056b3;
        }

        /* --- Tableau --- */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
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

        /* --- Actions (Edit/Delete) --- */
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
        
        /* Cache l'écho PHP initial */
        .php-output {
            display: none; 
        }
    </style>
</head>
<body>
    
    <div class="sidebar">
        <h2>Dashboard</h2>
        <nav>
            <a href="../home.php">🏠 Tableau de Bord</a> 
            <a href="index_cours.php">📚 Gérer les Cours</a>
            <a href="index_equipement.php" class="active">⚙️ Gérer les Équipements</a>
            <a href="index_cours_equipement.php">🔗 Associations</a>
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
                
                <h1>⚙️ Gestion des Équipements</h1>

                <a href="./add/add_equipement.php" class="add-button">➕ Ajouter un équipement</a>

                <form method="GET" action="" class="filter-form">
                    <label for="filtre">Filtrer par type :</label>
                    <select name="filtre" id="filtre">
                        <option value="all" <?= $filtre == 'all' ? 'selected' : '' ?>>Tous les Types</option>
                        <option value="Cardio" <?= $filtre == 'Cardio' ? 'selected' : '' ?>>Cardio</option>
                        <option value="Accessoire" <?= $filtre == 'Accessoire' ? 'selected' : '' ?>>Accessoire</option>
                        </select>
                    <button type="submit">Appliquer le Filtre</button>
                </form>

                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Type Équipement</th>
                            <th>Quantité Disponible</th>
                            <th>État</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['nom']) ?></td>
                                <td><?= htmlspecialchars($row['type_equipement']) ?></td>
                                <td><?= htmlspecialchars($row['quantite_disponible']) ?></td>
                                <td><?= htmlspecialchars($row['etat']) ?></td>

                                <td>
                                    <a href="./update/update_equipement.php?id=<?= $row['id_equipement'] ?>" class="action-link edit">✏️ Edit</a> 
                                    <span style="color:#ccc; margin-right:10px;">|</span>
                                    <a href="./delete/delete_equipement.php?id=<?= $row['id_equipement'] ?>" class="action-link delete" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet équipement ?')">🗑️ Delete</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                        
                        <?php if (mysqli_num_rows($result) == 0): ?>
                            <tr>
                                <td colspan="5" style="text-align: center; color: #6c757d; padding: 20px;">
                                    Aucun équipement trouvé pour ce filtre.
                                </td>
                            </tr>
                        <?php endif; ?>
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