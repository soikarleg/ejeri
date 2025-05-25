import svgwrite
from PIL import Image

# Ouvrir l'image JPG
image = Image.open("/media/flxxx/stock3/00-Professionnel/00-PRO-SIGNATURES/logo.png")

# Créer un fichier SVG
dwg = svgwrite.Drawing('/media/flxxx/stock3/00-Professionnel/00-PRO-SIGNATURES/logo_01.svg', profile='tiny', size=(image.width, image.height))

# Convertir l'image en pixels en une liste de couleurs
pixels = list(image.getdata())

# Parcourir les pixels et dessiner des rectangles correspondants dans le SVG
for y in range(image.height):
    for x in range(image.width):
        color = pixels[y * image.width + x]
        r, g, b = color[:3]
        rect = svgwrite.shapes.Rect(insert=(x, y), size=(1, 1), fill=f"rgb({r},{g},{b})")
        dwg.add(rect)

# Enregistrer le fichier SVG
dwg.save()
