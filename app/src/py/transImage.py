from PIL import Image

# Plage de couleur


def generer_plage_couleurs(couleur_depart, couleur_arret, nombre_etapes):
    plage_de_couleur = []

    # Calcul des couleurs intermédiaires
    for i in range(nombre_etapes + 1):
        r = couleur_depart[0] - ((couleur_depart[0] -
                                 couleur_arret[0]) * i // nombre_etapes)
        g = couleur_depart[1] - ((couleur_depart[1] -
                                 couleur_arret[1]) * i // nombre_etapes)
        b = couleur_depart[2] - ((couleur_depart[2] -
                                 couleur_arret[2]) * i // nombre_etapes)
        # a = couleur_depart[3] - ((couleur_depart[3] -
        #                          couleur_arret[3]) * i // nombre_etapes)
        plage_de_couleur.append((r, g, b))

    return plage_de_couleur


def rendre_fond_blanc_transparent(image_path, output_path):
    # Ouvrir l'image avec Pillow
    image = Image.open(image_path)

    # Obtenir les dimensions de l'image
    largeur, hauteur = image.size

    # Créer une nouvelle image avec un fond transparent
    image_transparente = Image.new("RGBA", (largeur, hauteur))

    couleur_depart = (255, 255, 255)
    couleur_arret = (174, 250, 255)
    nombre_etapes = 5
    couleurs = generer_plage_couleurs(
        couleur_depart, couleur_arret, nombre_etapes)
    print(couleurs)
    # Parcourir chaque pixel de l'image d'origine
    for x in range(largeur):
        for y in range(hauteur):
            # Obtenir la couleur du pixel
            pixel = image.getpixel((x, y))

            for couleur in couleurs:
                if pixel[:3] != couleur:
                    image_transparente.putpixel((x, y), pixel)

    # Enregistrer l'image avec fond transparent
    image_transparente.save(output_path, format="PNG")

# ! Bonne version avec bon résultat. Jouer avec tolérance.
def blanc(image_path, output_path, tolerance=30):
    image = Image.open(image_path)
    largeur, hauteur = image.size
    image_transparente = Image.new("RGBA", (largeur, hauteur))
    for x in range(largeur):
        for y in range(hauteur):
            pixel = image.getpixel((x, y))

            # Vérifier si le pixel est presque blanc en utilisant la tolérance
            r, g, b = pixel[:3]
            if r >= 255 - tolerance and g >= 255 - tolerance and b >= 255 - tolerance:
                pixel = (0, 0, 0, 0)  # Rendre le pixel transparent

            # Copier le pixel dans la nouvelle image
            image_transparente.putpixel((x, y), pixel)

    # Enregistrer l'image avec fond transparent
    image_transparente.save(output_path, format="PNG")


if __name__ == "__main__":
    # Remplacez par le chemin de votre image
    image_path = "/media/flxxx/stock3/00-Professionnel/00-PRO-SIGNATURES/signa.png"
    # Chemin de l'image de sortie
    output_path = "/media/flxxx/stock3/00-Professionnel/00-PRO-SIGNATURES/tampon_sci_01.png"

    blanc(image_path, output_path)
