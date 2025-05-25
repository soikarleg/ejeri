#!/usr/bin/env python3
import os
import sys
import re

MODULES = ["app", "pro", "cli", "admin", "manop", "webapp"]
CSS_SYMLINK = os.path.join("assets", "css", "enooki.css")
CSS_TARGET = os.path.normpath(os.path.join("..", "shared", "assets", "css", "enooki.css"))
CSS_LINK_TAG = '<link href="assets/css/enooki.css" rel="stylesheet">'

# Fichiers principaux à vérifier par module
MAIN_FILES = {
    "app": [os.path.join("inc", "head.php"), "index.php"],
    "pro": ["index.php"],
    "cli": ["index.php"],
    "admin": ["index.php"],
    "manop": ["index.php"],
    "webapp": ["index.php"]
}

def check_symlink(module):
    path = os.path.join(module, CSS_SYMLINK)
    if not os.path.islink(path):
        return False, f"{path} n'est pas un lien symbolique."
    target = os.readlink(path)
    # Autoriser les chemins relatifs ou absolus
    if not (target.endswith("shared/assets/css/enooki.css") or os.path.abspath(target) == os.path.abspath(os.path.join(module, CSS_SYMLINK, '../../..', CSS_TARGET))):
        return False, f"{path} ne pointe pas vers le CSS commun (cible: {target})."
    return True, None

def check_link_inclusion(module):
    files = MAIN_FILES.get(module, [])
    for f in files:
        fpath = os.path.join(module, f)
        if not os.path.isfile(fpath):
            continue
        with open(fpath, encoding="utf-8") as fh:
            content = fh.read()
            # Recherche stricte ou tolérante (espaces, guillemets)
            if re.search(r'<link\s+href=["\']assets/css/enooki.css["\']\s+rel=["\']stylesheet["\']', content):
                return True, None
    return False, f"Balise <link> manquante dans {module}/index.php ou {module}/inc/head.php."

def main():
    errors = []
    for module in MODULES:
        ok, msg = check_symlink(module)
        if not ok:
            errors.append(msg)
        ok, msg = check_link_inclusion(module)
        if not ok:
            errors.append(msg)
    if errors:
        print("\n[ERREUR] Problèmes détectés :")
        for e in errors:
            print("-", e)
        sys.exit(1)
    print("[OK] Tous les symlinks et inclusions CSS sont corrects.")
    sys.exit(0)

if __name__ == "__main__":
    main()
