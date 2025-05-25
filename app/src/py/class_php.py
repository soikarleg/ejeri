import re

# Charger le contenu de la classe PHP
with open('myclass/flxxx/src/Compte.php', 'r') as php_file:
    php_code = php_file.read()

# Utiliser une expression régulière pour trouver les noms de fonctions après "public function"
function_matches = re.findall(r'public function (\w+)', php_code)

# Liste des noms de fonctions
function_names = function_matches

# Afficher les noms des fonctions
for function_name in function_names:
    print(function_name)
