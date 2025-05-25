import requests
from bs4 import BeautifulSoup
import csv

# URL de la page web que vous souhaitez analyser
url = "https://www.fdesouche.com/2023/10/18/fusillade-ce-lundi-soir-dans-le-centre-de-bruxelles-il-y-aurait-deux-morts-et-plusieurs-blesses/"

# Récupérer le contenu de la page web
response = requests.get(url)
html = response.text

# Analyser le HTML avec BeautifulSoup
soup = BeautifulSoup(html, 'html.parser')

# Trouver toutes les balises <p> et extraire leur texte
paragraphs = soup.find_all('p')

# Créer un ensemble pour stocker les mots distincts
unique_words = set()

# Extraire et diviser les mots de chaque balise <p>
for paragraph in paragraphs:
    text = paragraph.get_text()
    words = text.split()

    # Ajouter les mots distincts à l'ensemble unique_words
    unique_words.update(words)

# Créer un fichier CSV pour stocker les mots distincts
with open('mots_distincts.csv', 'w', newline='') as csvfile:
    csv_writer = csv.writer(csvfile)
    csv_writer.writerow(['Mot'])  # Écrire l'en-tête du CSV

    # Écrire les mots distincts dans le fichier CSV
    for word in unique_words:
        csv_writer.writerow([word])

print("Les mots distincts des balises <p> ont été stockés dans 'mots_distincts.csv'")
