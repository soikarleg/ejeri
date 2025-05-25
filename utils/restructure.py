import os

# Structure primaire du projet (racine)
ROOT_STRUCTURE = [
    'admin', 'api', 'app', 'cli', 'manop', 'pro', 'public', 'shared', 'sql', 'vendor', 'webapp'
]

# Structure secondaire MVC pour chaque module principal
MVC_STRUCTURE = [
    'classes', 'config', 'controllers', 'models', 'views', 'public', 'assets', 'vendor', 'tests', 'logs'
]

# Fichiers standards à la racine de chaque module
MODULE_FILES = [
    'README.md', 'LICENSE', 'composer.json', 'composer.lock', 'index.php', '.htaccess', '.gitignore'
]

# Fichiers publics à ajouter dans /public ou /assets
ASSETS_STRUCTURE = [
    'css', 'img', 'js', 'scss', 'vendor'
]

# Modules à traiter (présents dans la trame SaaS)
MODULES = ['admin', 'api', 'app', 'cli', 'manop', 'pro', 'webapp']

# Fonction utilitaire pour créer un dossier si absent
def safe_mkdir(path):
    if not os.path.exists(path):
        os.makedirs(path)
        print(f"Créé : {path}")

# Fonction utilitaire pour créer un fichier si absent
def safe_touch(path):
    if not os.path.exists(path):
        with open(path, 'w') as f:
            pass
        print(f"Créé : {path}")

# 1. Création de la structure racine
for folder in ROOT_STRUCTURE:
    safe_mkdir(folder)

# 2. Création de la structure MVC dans chaque module principal
for module in MODULES:
    for sub in MVC_STRUCTURE:
        safe_mkdir(os.path.join(module, sub))
    # Création des fichiers standards
    for f in MODULE_FILES:
        safe_touch(os.path.join(module, f))
    # Création des sous-dossiers d'assets
    assets_path = os.path.join(module, 'assets')
    for asset in ASSETS_STRUCTURE:
        safe_mkdir(os.path.join(assets_path, asset))

# 3. Création des dossiers/fichiers globaux
for asset in ASSETS_STRUCTURE:
    safe_mkdir(os.path.join('assets', asset))

# 4. Ajout de fichiers globaux à la racine si absents
for f in MODULE_FILES:
    safe_touch(f)

print("\nStructure vérifiée et complétée avec succès !")
