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
        plage_de_couleur.append((r, g, b, 1))

    return plage_de_couleur


def rendre_fond_blanc_transparent(image_path, output_path):
    image = Image.open(image_path)

    # Obtenir les dimensions de l'image
    largeur, hauteur = image.size

    # Créer une nouvelle image avec un fond transparent
    image_transparente = Image.new("RGBA", (largeur, hauteur))

    # Parcourir chaque pixel de l'image d'origine
    for x in range(largeur):
        for y in range(hauteur):
            # Obtenir la couleur du pixel
            pixel = image.getpixel((x, y))

            # Si le pixel n'est pas blanc (255, 255, 255), copiez-le dans la nouvelle image
            if pixel[:3] != (255, 255, 255):
                image_transparente.putpixel((x, y), pixel)

    # Enregistrer l'image avec fond transparent
    image_transparente.save(output_path, format="PNG")


if __name__ == "__main__":
    # Remplacez par le chemin de votre image
    image_path = "/media/flxxx/stock3/00-Professionnel/00-PRO-SIGNATURES/signa.png"
    # Chemin de l'image de sortie
    output_path = "/media/flxxx/stock3/00-Professionnel/00-PRO-SIGNATURES/tampon_sci.png"

    rendre_fond_blanc_transparent(image_path, output_path)
