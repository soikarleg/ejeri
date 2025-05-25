import os

def create_structure(base_path):
    # Structure des répertoires et fichiers
    structure = {
    "uploads":["fiche_00/image.png","fiche_01/image.png"],
        "config": ["db.php"],
        "includes": ["auth.php", "functions.php", "fpdf/"],
        "public": [
            "login.php",
            "logout.php",
            "dashboard.php",
            "fiches/create.php",
            "fiches/edit.php",
            "fiches/view.php",
            "fiches/generate_pdf.php",
            "fiches/upload_image.php",
            "users/list.php",
            "users/create.php",
            "users/edit.php"
        ],
        "assets": ["css/mystyle.css","js/myscript.js","img/logo.png","bootstrap"],
    }

    # Créer d'abord les répertoires et sous-répertoires
    for directory, files in structure.items():
        for file in files:
            if file.endswith('/'):
                # C'est un sous-répertoire
                file_path = os.path.join(base_path, directory, file)
            else:
                # C'est un fichier, extraire le chemin du répertoire
                file_path = os.path.join(base_path, directory, os.path.dirname(file))

            if not os.path.exists(file_path):
                os.makedirs(file_path, exist_ok=True)

    # Ensuite, créer les fichiers
    for directory, files in structure.items():
        for file in files:
            if not file.endswith('/'):
                file_path = os.path.join(base_path, directory, file)
                if not os.path.exists(file_path):
                    with open(file_path, 'w') as f:
                        f.write("")

    # Créer index.php dans le répertoire de base
    with open(os.path.join(base_path, "index.php"), 'w') as f:
        f.write("")

if __name__ == "__main__":
    base_path = "manop"
    if not os.path.exists(base_path):
        os.makedirs(base_path)
    create_structure(base_path)
    print(f"Structure created at {base_path}")

