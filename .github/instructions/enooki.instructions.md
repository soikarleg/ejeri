---
applyTo: "**"
---

## Projet Enooki

On utilise systématiquement PROJECT_ROOT pour inclure les fichiers, ce qui permet de ne pas avoir à se soucier du chemin d'accès.

### Structure

```
.
├── admin
│   ├── assets
│   ├── classes
│   ├── config
│   ├── controllers
│   ├── logs
│   ├── models
│   ├── public
│   ├── tests
│   ├── vendor
│   └── views
├── api
│   ├── api
│   ├── api.bak
│   ├── assets
│   ├── classes
│   ├── config
│   ├── controllers
│   ├── inc
│   ├── logs
│   ├── models
│   ├── public
│   ├── tests
│   ├── vendor
│   └── views
├── app
│   ├── assets
│   ├── class
│   ├── classes
│   ├── config
│   ├── controllers
│   ├── documents
│   ├── inc
│   ├── log
│   ├── logs
│   ├── models
│   ├── public
│   ├── src
│   ├── tests
│   ├── uploads
│   ├── vendor
│   └── views
├── assets
│   ├── css
│   ├── img
│   ├── js
│   ├── scss
│   └── vendor
├── class
├── cli
│   ├── assets
│   ├── class
│   ├── classes
│   ├── config
│   ├── controllers
│   ├── logs
│   ├── models
│   ├── public
│   ├── section
│   ├── tests
│   ├── vendor
│   └── views
├── config
├── forms
├── log
├── manop
│   ├── assets
│   ├── classes
│   ├── config
│   ├── controllers
│   ├── includes
│   ├── logs
│   ├── models
│   ├── public
│   ├── tests
│   ├── uploads
│   ├── vendor
│   └── views
├── pro
│   ├── assets
│   ├── classes
│   ├── config
│   ├── controllers
│   ├── logs
│   ├── models
│   ├── public
│   ├── tests
│   ├── vendor
│   └── views
├── public
├── sections
├── shared
├── sql
├── utils
├── vendor
│   ├── composer
│   └── phpmailer
└── webapp
    ├── assets
    ├── classes
    ├── config
    ├── controllers
    ├── logs
    ├── models
    ├── public
    ├── tests
    ├── vendor
    └── views


```

### Base de donnée

- La base de données utilisée est hébergée chez ionos et est détaillée dans config/mysql.php

Dans mon contexte et sur le .sql déjà fournis (propser les ALTER de correction)

- users = table des données de connexion des utilisateurs intervenants et utilisateurs clients (email, password)
- clients = table des informations de chaque client (civilite,nom, prenom)
- intervenants = table des informations de chaque intervenant (civilite,nom, prenom)
- adresses = table des adresses clients (facturation, intervention) utilisateurs (personnel) organisations (principale, secondaire)
- telephones = table des telephones clients, utilisateurs et organisations (numero, type (fixe,portable), attachement adresse, description)
- notes = table des notes concernants clients et utilisateur (propose des champs)
- devis = table des entetes de devis (propose les champs et les clefs)
- factures = table des entetes de factures (propose les champs et les clefs)
- corps_devis = table des contenus ligne par ligne des devis (propose les champs et les clefs)
- corps_factures = table des contenus ligne par ligne des factures (propose les champs et les clefs)
- organisations = table des organisations (propose les champs et les clefs)
- echanges = table des echanges entre les utilisateurs et les clients (propose les champs et les clefs)

  Corrige et propose des améliorations sur cette trame de database qui a pour objectif de tout enregistrer sur l'activité.
  Propose également trigger et vues ?

- trigger : exemple de trigger
  - sur la table devis pour mettre à jour le statut de la facture
  - sur la table factures pour mettre à jour le statut du devis
- vues : exemple
  - une vue pour les devis en cours
  - une vue pour les factures en cours
  - une vue pour les clients avec le plus de factures
  - une vue pour les intervenants avec le plus de devis
  - une vue pour les utilisateurs avec le plus de notes
  - une vue pour les utilisateurs avec le plus de clients
  - une vue pour les utilisateurs avec le plus de factures
  - une vue pour les utilisateurs avec le plus de devis

### CSS

- Les fonds de formulaire sont #404356
- Oubli la regle pour les bouttons de 'relations.instructions'
