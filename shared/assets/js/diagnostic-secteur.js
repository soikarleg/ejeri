// Script de diagnostic pour les problèmes de géolocalisation
console.log('🔍 Diagnostic de géolocalisation EJERI démarré');

// Vérification des conteneurs
const diagnostics = {
    secteurContainer: document.getElementById('secteur-info'),
    teamContainer: document.getElementById('team-secteur'),
    selecteur: document.getElementById('secteur-selecteur'),
    scriptLoaded: typeof SecteurGeolocalisation !== 'undefined',
    apiReachable: false
};

// Test de connectivité API
fetch('/api/secteur/all')
    .then(response => {
        diagnostics.apiReachable = response.ok;
        return response.json();
    })
    .then(data => {
        console.log('✅ API secteurs accessible:', data);
    })
    .catch(error => {
        console.error('❌ Erreur API secteurs:', error);
    });

// Observer les mutations DOM pour détecter les rechargements
const observer = new MutationObserver(function(mutations) {
    mutations.forEach(function(mutation) {
        if (mutation.target.id === 'team-secteur' || mutation.target.id === 'secteur-info') {
            console.log('🔄 Rechargement détecté sur:', mutation.target.id);
            console.log('Nouvelle taille:', mutation.target.innerHTML.length, 'caractères');
        }
    });
});

// Observer les conteneurs
if (diagnostics.teamContainer) {
    observer.observe(diagnostics.teamContainer, {
        childList: true,
        subtree: true
    });
}

if (diagnostics.secteurContainer) {
    observer.observe(diagnostics.secteurContainer, {
        childList: true,
        subtree: true
    });
}

// Afficher les diagnostics
setTimeout(() => {
    console.log('📊 Rapport de diagnostic:', diagnostics);
    
    if (!diagnostics.scriptLoaded) {
        console.warn('⚠️ Script de géolocalisation non chargé');
    }
    
    if (!diagnostics.apiReachable) {
        console.warn('⚠️ API secteurs non accessible');
    }
    
    if (!diagnostics.teamContainer) {
        console.warn('⚠️ Conteneur #team-secteur non trouvé');
    }
    
    if (!diagnostics.secteurContainer) {
        console.warn('⚠️ Conteneur #secteur-info non trouvé');
    }
}, 2000);
