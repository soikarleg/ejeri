import pdfplumber
import pdfplumber
import csv
import re

file = 'releve_q_2023'

pdf_path = 'documents/pdf/releves/'+file+'.pdf'
csv_path = 'documents/pdf/releves/csv/'+file+'.csv'

data = []

# Pattern pour identifier les dates au format dd/mm/yyyy
# date_pattern = r'\d{2}/\d{2}/\d{4}'
# Pattern pour identifier les dates au format dd/mm/yyyy
# Pattern pour identifier les dates au format dd/mm/yyyy
date_pattern = r'\d{2}/\d{2}'
float_pattern = r'\b(?:\d{1,4}(?:\.\d{1,2})?)\b'
description_pattern = r'b.*'  # Ajoutez votre pattern pour la description ici

with pdfplumber.open(pdf_path) as pdf:
    for page in pdf.pages:
        for line in page.extract_text().split('\n'):
            if re.match(date_pattern, line):
                # Utiliser une expression régulière pour découper la ligne en éléments
                match = re.match(rf'({date_pattern}) ({description_pattern}) ({float_pattern} EUR)', line)
                if match:
                    date = match.group(1)
                    description = match.group(2)
                    amount = match.group(3)
                    data.append(f'{date}; {description.strip()}; {amount}')
                    print(data)

# Écrire les données dans un fichier CSV
with open(csv_path, 'w', newline='', encoding='utf-8') as csv_file:
    csv_writer = csv.writer(csv_file)
    csv_writer.writerows(data)

print("Les lignes commençant par une date ont été écrites dans le fichier CSV.")


# pdf_path = "documents/pdf/releve_072023-cm.pdf"
# text = ''

# with pdfplumber.open(pdf_path) as pdf:
#     for page in pdf.pages:
#         text += page.extract_text()

# print(text)
