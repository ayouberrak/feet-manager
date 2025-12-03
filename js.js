// Données de simulation (inchangées)
let COURS_DATA = [
    { id: 1, nom: 'Hatha Flow', categorie: 'Yoga', date: '2025-12-10', heure: '18:00', duree: 60, max_participants: 15 },
    { id: 2, nom: 'Body Pump', categorie: 'Musculation', date: '2025-12-10', heure: '19:15', duree: 45, max_participants: 20 },
    { id: 3, nom: 'RPM - Cycling', categorie: 'Cardio', date: '2025-12-11', heure: '07:30', duree: 50, max_participants: 10 },
    { id: 4, nom: 'Zumba', categorie: 'Cardio', date: '2025-12-11', heure: '18:30', duree: 60, max_participants: 30 }
];
let EQUIPEMENTS_DATA = [
    { id: 1, nom: 'Tapis de Course Modèle A', type: 'Tapis de course', quantite: 12, etat: 'Bon' },
    { id: 2, nom: 'Haltères 5kg', type: 'Haltères', quantite: 30, etat: 'Moyen' },
    { id: 3, nom: 'Ballon de Yoga', type: 'Ballons', quantite: 15, etat: 'Bon' },
    { id: 4, nom: 'Vieux Rameur (HS)', type: 'Rameur', quantite: 5, etat: 'À remplacer' } // Ajout d'un équipement à remplacer
];

let nextCoursId = COURS_DATA.length + 1;
let nextEquipementId = EQUIPEMENTS_DATA.length + 1;

// --- Fonctions d'Affichage et de Navigation (Amélioré) ---

function showSection(sectionId, element) {
    // Masquer toutes les sections
    document.querySelectorAll('.content-section').forEach(section => {
        section.classList.add('hidden');
    });
    // Afficher la section demandée
    document.getElementById(sectionId).classList.remove('hidden');

    // Mettre à jour la navigation (gestion de la classe 'active')
    document.querySelectorAll('.nav-item').forEach(item => {
        item.classList.remove('active');
    });
    if (element) {
        element.classList.add('active');
    }

    // Rendre les données spécifiques à la section
    if (sectionId === 'dashboard') {
        renderDashboard();
    } else if (sectionId === 'gestion-cours') {
        renderCoursTable();
    } else if (sectionId === 'gestion-equipements') {
        renderEquipementsTable();
    }
}

function openModal(modalId, itemId = null) {
    // ... (Fonction openModal inchangée)
    const modal = document.getElementById(modalId);
    modal.style.display = 'block';

    if (modalId === 'cours-modal') {
        populateCoursForm(itemId);
    } else if (modalId === 'equipement-modal') {
        populateEquipementForm(itemId);
    }
}

function closeModal(modalId) {
    // ... (Fonction closeModal inchangée)
    document.getElementById(modalId).style.display = 'none';
}

// --- Dashboard Logic (KPIs & Graphiques) (Amélioré) ---

function renderDashboard() {
    // 1. KPIs
    document.getElementById('kpi-cours').textContent = COURS_DATA.length;
    
    const totalQuantiteEquipements = EQUIPEMENTS_DATA.reduce((acc, eq) => acc + eq.quantite, 0);
    document.getElementById('kpi-equipements').textContent = totalQuantiteEquipements;

    const totalARemplacer = EQUIPEMENTS_DATA.filter(eq => eq.etat === 'À remplacer').length;
    document.getElementById('kpi-a-remplacer').textContent = totalARemplacer;

    // 2. Répartition des Cours par Type
    const coursByCategorie = COURS_DATA.reduce((acc, cours) => {
        acc[cours.categorie] = (acc[cours.categorie] || 0) + 1;
        return acc;
    }, {});
    
    createChart('coursChart', 'doughnut', 'Répartition des Cours', Object.keys(coursByCategorie), Object.values(coursByCategorie));

    // 3. Répartition des Équipements par Type
    const equipementsByType = EQUIPEMENTS_DATA.reduce((acc, eq) => {
        acc[eq.type] = (acc[eq.type] || 0) + eq.quantite;
        return acc;
    }, {});

    createChart('equipementsChart', 'bar', 'Quantité Équipements par Type', Object.keys(equipementsByType), Object.values(equipementsByType));
}

// ... (Le reste des fonctions `createChart`, `renderCoursTable`, `populateCoursForm`, 
// `renderEquipementsTable`, `populateEquipementForm`, `deleteItem` restent identiques au script précédent) ...

function createChart(canvasId, type, title, labels, data) {
    if (window[canvasId + 'ChartInstance']) {
        window[canvasId + 'ChartInstance'].destroy();
    }
    
    const ctx = document.getElementById(canvasId).getContext('2d');
    window[canvasId + 'ChartInstance'] = new Chart(ctx, {
        type: type,
        data: {
            labels: labels,
            datasets: [{
                label: title,
                data: data,
                backgroundColor: [ // Couleurs adaptées au mode sombre
                    '#00CC99', // Accent vert
                    '#4A90E2', // Bleu
                    '#FFC371', // Jaune/Orange
                    '#FF6B6B' // Rouge
                ],
                borderColor: '#2B2B3E', 
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    labels: {
                        color: '#E0E0E0' // Texte de légende clair
                    }
                }
            },
            scales: (type === 'bar') ? { 
                y: { 
                    beginAtZero: true,
                    grid: { color: '#3d3d57' }, // Grille foncée
                    ticks: { color: '#E0E0E0' }
                },
                x: {
                    grid: { color: '#3d3d57' },
                    ticks: { color: '#E0E0E0' }
                }
            } : {}
        }
    });
}

function renderCoursTable() {
    const tbody = document.getElementById('cours-table').querySelector('tbody') || document.createElement('tbody');
    if (!document.getElementById('cours-table').querySelector('tbody')) {
        document.getElementById('cours-table').innerHTML = `
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Catégorie</th>
                    <th>Date</th>
                    <th>Heure</th>
                    <th>Durée (min)</th>
                    <th>Max. Participants</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody></tbody>
        `;
    }
    tbody.innerHTML = ''; 

    COURS_DATA.forEach(cours => {
        const row = tbody.insertRow();
        row.insertCell().textContent = cours.nom;
        row.insertCell().textContent = cours.categorie;
        row.insertCell().textContent = cours.date;
        row.insertCell().textContent = cours.heure;
        row.insertCell().textContent = cours.duree;
        row.insertCell().textContent = cours.max_participants;
        
        const actionsCell = row.insertCell();
        actionsCell.innerHTML = `
            <button class="action-btn edit-btn" onclick="openModal('cours-modal', ${cours.id})"><i class="fas fa-edit"></i></button>
            <button class="action-btn delete-btn" onclick="deleteItem(${cours.id}, 'cours')"><i class="fas fa-trash-alt"></i></button>
        `;
    });
}

function renderEquipementsTable() {
    const tbody = document.getElementById('equipements-table').querySelector('tbody') || document.createElement('tbody');
     if (!document.getElementById('equipements-table').querySelector('tbody')) {
        document.getElementById('equipements-table').innerHTML = `
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Type</th>
                    <th>Quantité Dispo</th>
                    <th>État</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody></tbody>
        `;
    }
    tbody.innerHTML = ''; 

    EQUIPEMENTS_DATA.forEach(equipement => {
        const row = tbody.insertRow();
        row.insertCell().textContent = equipement.nom;
        row.insertCell().textContent = equipement.type;
        row.insertCell().textContent = equipement.quantite;
        // Ajout d'une classe pour styliser l'état
        const etatCell = row.insertCell();
        etatCell.innerHTML = `<span class="etat-${equipement.etat.replace(' ', '-').toLowerCase()}">${equipement.etat}</span>`;

        const actionsCell = row.insertCell();
        actionsCell.innerHTML = `
            <button class="action-btn edit-btn" onclick="openModal('equipement-modal', ${equipement.id})"><i class="fas fa-edit"></i></button>
            <button class="action-btn delete-btn" onclick="deleteItem(${equipement.id}, 'equipement')"><i class="fas fa-trash-alt"></i></button>
        `;
    });
}

// Initialisation de l'application au chargement de la page
document.addEventListener('DOMContentLoaded', () => {
    // Clic sur l'élément de navigation du dashboard pour l'initialisation
    showSection('dashboard', document.getElementById('nav-dashboard'));
});
// ... (le reste des fonctions JS pour les formulaires et la suppression est conservé)