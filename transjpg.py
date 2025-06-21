import os
import shutil

def copy_jpg_files(source_dir, destination_dir):
    # Parcourir tous les fichiers et répertoires dans le répertoire source
    for root, dirs, files in os.walk(source_dir):
        for file in files:
            # Vérifier si le fichier est un .jpg
            if file.lower().endswith('.png'):
                # Chemin complet du fichier source
                source_file = os.path.join(root, file)

                # Chemin relatif du fichier par rapport au répertoire source
                relative_path = os.path.relpath(root, source_dir)

                # Chemin complet du répertoire de destination
                destination_subdir = os.path.join(destination_dir, relative_path)

                # Créer le répertoire de destination s'il n'existe pas
                os.makedirs(destination_subdir, exist_ok=True)

                # Chemin complet du fichier de destination
                destination_file = os.path.join(destination_subdir, file)

                # Copier le fichier
                shutil.copy2(source_file, destination_file)
                print(f'Copié : {source_file} -> {destination_file}')
                
def copy_jpg_files_flat(source_dir, destination_dir):
    # Parcourir tous les fichiers et répertoires dans le répertoire source
    for root, dirs, files in os.walk(source_dir):
        for file in files:
            # Vérifier si le fichier est un .jpg
            if file.lower().endswith('.png'):
                # Chemin complet du fichier source
                source_file = os.path.join(root, file)

                # Chemin complet du fichier de destination
                destination_file = os.path.join(destination_dir, file)

                # Copier le fichier
                shutil.copy2(source_file, destination_file)
                print(f'Copié : {source_file} -> {destination_file}')

# Exemple d'utilisation
source_directory = '/media/otto/stock3/A_CLASSER'  # Remplacez par le chemin de votre disque source
destination_directory = '/media/otto/SAUVEGARDE/transpng'  # Remplacez par le chemin de votre disque de destination

copy_jpg_files_flat(source_directory, destination_directory)
