<?php 
    require "./database/db.php";

    session_start();
    
    if (!isset($_SESSION['nom'])) {
    header("Location: index.php");
    exit;
    }
    
    $req ="SELECT COUNT(*) as total FROM COURS";
    $result = mysqli_query($conn,$req);
    $row = mysqli_fetch_assoc($result);
    $total_cours = $row['total'] ?? 0;
    
    $reqe ="SELECT COUNT(*) as totale FROM EQUIPEMENT ";
    $resulta = mysqli_query($conn,$reqe);
    $roww = mysqli_fetch_assoc($resulta);
    $total_equipement = $roww['totale'] ?? 0; 

    $max_value = max($total_cours, $total_equipement, 1);

    $percent_cours = ($total_cours / $max_value) * 100;
    $percent_equipement = ($total_equipement / $max_value) * 100;
    
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord | Accueil</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #007bff; 
            --secondary-color: #28a745; 
            --sidebar-width: 250px;
            --background-color: #f4f7f6; 
            --card-background: #ffffff;
            --sidebar-background: #343a40; 
            --text-color: #343a40;
            --sidebar-text-color: #ffffff;
            --sidebar-hover-color: #495057;
            --dropdown-bg: #ffffff;
            --dropdown-border: #e0e0e0;
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

        /* --- Contenu Principal & En-tête --- */
        .main-container {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }

        .top-header {
            background-color: var(--card-background);
            padding: 1rem 2rem;
            display: flex;
            justify-content: flex-end; 
            align-items: center;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08); 
            border-bottom: 1px solid #e0e0e0;
        }
        /* Styles pour profile-dropdown, profile-button, etc. (conservés) */
        .profile-dropdown { position: relative; display: inline-block; }
        .profile-button {
            display: flex; align-items: center; background: none; border: none; cursor: pointer;
            padding: 8px 15px; border-radius: 25px; transition: background-color 0.3s;
        }
        .profile-button:hover, .profile-button.active { background-color: var(--background-color); }
        .profile-icon {
            width: 30px; height: 30px; background-color: var(--primary-color); color: white;
            border-radius: 50%; display: flex; align-items: center; justify-content: center;
            font-weight: 600; margin-right: 10px; font-size: 1.1rem;
        }
        .profile-name { font-weight: 600; color: var(--text-color); margin-right: 10px; }
        .arrow-icon { transition: transform 0.3s; }
        .profile-button.active .arrow-icon { transform: rotate(180deg); }
        .dropdown-content {
            display: none; position: absolute; right: 0; background-color: var(--dropdown-bg);
            min-width: 200px; box-shadow: 0 8px 16px 0 rgba(0,0,0,0.2); z-index: 1000;
            border-radius: 8px; border: 1px solid var(--dropdown-border); margin-top: 5px;
            opacity: 0; transform: translateY(-10px); transition: opacity 0.3s ease-out, transform 0.3s ease-out;
        }
        .dropdown-content.show { display: block; opacity: 1; transform: translateY(0); }
        .dropdown-header {
            padding: 10px 15px; font-size: 1.1rem; font-weight: 600; color: var(--primary-color);
            border-bottom: 1px solid var(--dropdown-border);
        }
        .dropdown-content a {
            color: var(--text-color); padding: 12px 15px; text-decoration: none; display: block;
            font-size: 0.95rem; transition: background-color 0.3s;
        }
        .dropdown-content a:hover { background-color: var(--background-color); }
        .dropdown-content .logout-link {
            border-top: 1px solid var(--dropdown-border); color: #dc3545; 
            font-weight: 600;
        }
        .dropdown-content .logout-link:hover { background-color: #f8d7da; }


        /* ------------------------- CONTENU PRINCIPAL & STATS ------------------------- */
        .main-content {
            padding: 2.5rem 2rem; 
            flex-grow: 1;
        }
        
        .main-content h1 {
             color: var(--primary-color);
             margin-top: 0;
             font-weight: 600;
             border-bottom: 2px solid var(--primary-color);
             display: inline-block;
             padding-bottom: 5px;
             margin-bottom: 25px;
        }
        
        /* Grille des cartes */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 25px; 
            margin-bottom: 2rem;
        }

        .stat-card {
            background-color: var(--card-background);
            padding: 2rem; 
            border-radius: 10px; 
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1); 
            transition: transform 0.3s ease, box-shadow 0.3s ease; 
            border-left: 5px solid var(--secondary-color);
        }
        
        .stat-card:hover {
            transform: translateY(-8px) scale(1.02); 
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15); 
        }
        
        .stat-card:nth-child(2) {
            border-left-color: var(--primary-color); 
        }

        .stat-card h3 {
            margin-top: 0;
            font-size: 1rem;
            color: var(--secondary-color);
            text-transform: uppercase;
            letter-spacing: 1.5px; 
            font-weight: 600;
        }
        
        .stat-card:nth-child(2) h3 {
            color: var(--primary-color);
        }

        .stat-value {
            font-size: 3rem; 
            font-weight: 700;
            color: var(--text-color);
            line-height: 1;
            margin-top: 0.8rem;
        }
        
        /* ------------------------- GRAPHIQUE À BARRES ------------------------- */
        .chart-card {
            background-color: var(--card-background);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            margin-top: 25px;
        }
        
        .chart-card h2 {
            font-size: 1.4rem;
            color: var(--text-color);
            border-bottom: 1px solid var(--dropdown-border);
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        
        .bar-chart {
            display: flex;
            flex-direction: column;
            gap: 20px;
            padding-right: 20px; /* Espace pour les labels */
        }
        
        .bar-wrapper {
            display: flex;
            align-items: center;
        }
        
        .bar-label {
            width: 150px; /* Largeur fixe pour les labels */
            font-weight: 600;
            font-size: 0.95rem;
            color: var(--text-color);
        }
        
        .bar-container {
            flex-grow: 1;
            height: 30px;
            background-color: var(--background-color);
            border-radius: 5px;
            position: relative;
        }
        
        .bar {
            height: 100%;
            border-radius: 5px;
            transition: width 1.5s ease-out;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            padding-right: 10px;
            box-sizing: border-box;
            font-weight: 700;
            font-size: 0.9rem;
        }
        
        .bar.cours {
            background-color: var(--secondary-color);
            color: white;
        }
        
        .bar.equipement {
            background-color: var(--primary-color);
            color: white;
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
            <a href="home.php" class="active">🏠 Tableau de Bord</a> 
            <a href="./page/index_cours.php">📚 Gérer les Cours</a>
            <a href="./page/index_equipement.php">⚙️ Gérer les Équipements</a>
            <a href="./page/index_cours_equipement.php">🔗 Associations</a>
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
                    <a href="./page/index_equipement.php">⚙️ Mes Équipements</a>
                    <a href="./page/index_cours.php">📚 Mes Cours</a>
                    
                    <a href="#" class="logout-link" onclick="document.getElementById('logoutForm').submit(); return false;">
                        ➡️ Déconnexion
                    </a>
                </div>
            </div>
            
            <form id="logoutForm" action="logout.php" method="POST" style="display: none;"></form>
            
        </div>

        <div class="main-content">
            <h1>Statistiques Générales</h1>

            <div class="stats-grid">
                
                <div class="stat-card">
                    <h3>TOTAL DES COURS</h3>
                    <div class="stat-value"><?= htmlspecialchars($total_cours) ?></div>
                </div>

                <div class="stat-card">
                    <h3>TOTAL DES ÉQUIPEMENTS</h3>
                    <div class="stat-value"><?= htmlspecialchars($total_equipement) ?></div>
                </div>

            </div>
            
            <div class="chart-card">
                <h2>📈 Comparaison Cours vs. Équipements</h2>
                
                <div class="bar-chart">
                    
                    <div class="bar-wrapper">
                        <div class="bar-label">Cours</div>
                        <div class="bar-container">
                            <div 
                                class="bar cours" 
                                style="width: <?= $percent_cours ?>%;">
                                <?= htmlspecialchars($total_cours) ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bar-wrapper">
                        <div class="bar-label">Équipements</div>
                        <div class="bar-container">
                            <div 
                                class="bar equipement" 
                                style="width: <?= $percent_equipement ?>%;">
                                <?= htmlspecialchars($total_equipement) ?>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
            
        </div>
    </div>
    
    <div class="php-output">
        <?php 
            echo 'hello'.$_SESSION['nom']; 
        ?>
    </div>

    <script>
        // Fonction pour basculer l'affichage du menu
        function toggleDropdown() {
            const content = document.getElementById("dropdownContent");
            const button = document.querySelector(".profile-button");
            
            content.classList.toggle("show");
            button.classList.toggle("active");
        }

        // Fermer le menu si l'utilisateur clique en dehors
        window.onclick = function(event) {
            if (!event.target.closest('.profile-dropdown')) {
                const dropdowns = document.getElementsByClassName("dropdown-content");
                const buttons = document.getElementsByClassName("profile-button");
                
                for (let i = 0; i < dropdowns.length; i++) {
                    if (dropdowns[i].classList.contains('show')) {
                        dropdowns[i].classList.remove('show');
                        buttons[i].classList.remove('active');
                    }
                }
            }
        }
    </script>

</body>
</html>