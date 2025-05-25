from reportlab.lib.pagesizes import A4
from reportlab.platypus import SimpleDocTemplate, Paragraph
from reportlab.lib.styles import getSampleStyleSheet
import re

# Charger le contenu de la classe PHP
with open('myclass/flxxx/src/Base.php', 'r') as php_file:
    php_code = php_file.read()

# Extraire les noms et les commentaires des fonctions publiques
# function_matches = re.findall(r'/\*\*(.*?)\*/\s*public function (\w+)\(', php_code, re.DOTALL)
function_matches = re.findall(r'public function (\w+)', php_code)

# Créer un fichier PDF
doc = SimpleDocTemplate("fonctions_class_Base.pdf", pagesize=A4)

# Créer une liste de paragraphes pour le PDF
story = []

for function_name in function_matches:
    # Utiliser le style par défaut pour le texte
    styles = getSampleStyleSheet()
    style = styles['Normal']

    # Ajouter le nom de la fonction comme titre
    story.append(Paragraph(f"<h3>Base.php</h3>", style))
    style = styles['Normal']
    story.append(Paragraph(f"<u>{function_name}</u>", style))

    # Ajouter le commentaire
    # story.append(Paragraph(comment, style))

    # Ajouter un saut de page après chaque fonction
    story.append(Paragraph("<br/>", style))

# Construire le PDF à partir de la liste de paragraphes
doc.build(story)
