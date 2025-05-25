import re
from reportlab.lib.pagesizes import A4
from reportlab.pdfgen import canvas

# Spécifiez le chemin vers votre fichier PHP
fichier_php = 'myclass/flxxx/src/Relances.php'

# Ouvrir le fichier et lire son contenu
with open(fichier_php, 'r') as fichier:
    contenu = fichier.read()

# Utiliser une expression régulière pour extraire les commentaires des fonctions
# fonctions_et_commentaires = re.findall(r'/\*\*(.*?)\*/\s*public function (\w+)\s*\(', contenu, re.DOTALL)
fonctions_et_commentaires = re.findall(r'public function (\w+)', contenu)

# Créer un fichier PDF au format lettre (A4)
nom_fichier_pdf = 'myclass/flxxx/src/doc_pdf/Relances.pdf'
c = canvas.Canvas(nom_fichier_pdf, pagesize=A4)

# Position verticale initiale
y = A4[1] - 50  # Commencer en haut de la page

# Hauteur maximale pour le contenu sur une page
hauteur_max = A4[1] - 100

# Fonction pour ajouter une nouvelle page


def nouvelle_page():
    c.showPage()
    c.setFont("Helvetica", 10)
    global y
    y = hauteur_max
    c.setFont("Helvetica-Bold", 10)
    c.drawString(30, 15, 'myclass/flxxx/src/Relances.pdf')


c.setFont("Helvetica-Bold", 10)
c.drawString(30, 15, 'myclass/flxxx/src/Relances.pdf')

for nom in fonctions_et_commentaires:
    lines = nom.strip().split('\n')
    hauteur_commentaire = (len(lines) + 1) * 12
    if y - hauteur_commentaire < 50:
        nouvelle_page()
    c.setFont("Helvetica-Bold", 10)
    c.drawString(30, y, nom)
    c.setFont("Helvetica", 10)
    y -= 12
    for line in lines:
        c.drawString(50, y, 'public function '+line.strip()+'()')
        y -= 12
    c.drawString(30, y, "-" * 200)
    y -= 12

c.save()
print("Fichier PDF créé :", nom_fichier_pdf)
