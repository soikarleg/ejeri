---
applyTo: "**"
---

## Instructions pour GitHub Copilot (Projet SaaS - Services à la personne)

## Objectif du projet

Développer une application SaaS de gestion d’activité quotidienne pour une entreprise de services de jardinage à domicile (services à la personne). Cette application a des extensions pour les clients (`cli/`), les intervenants (`pro/`), les utilisateurs (`app/` et `api/`) et pour les administrateurs (`admin/` et `manop/`).

## Règles de fonctionnement pour l'utilisateur

1. L'utilisateur admin **s'inscrit** à l'application
2. L'utilisateur admin **valide son adresse mail** grâce au lien envoyé via PHPMailer
3. L'utilisateur admin **se connecte** à l'application
4. L'utilisateur admin renseigne les **informations complètes de l'entreprise** via API de l'inpi ce qui créer un `compte_id`.
5. L'utilisateur admin renseigne ses **informations de contact**
6. L'utilisateur admin connecte son/ses **compte bancaires** via API Bridge
7. **Ces informations sont obligatoires pour que l'utilisateur admin puisse utiliser l'application**
8. L'utilisateur admin crée **éventuellement un compte pour un intervenant**. L'admin est considéré comme un intervenant.
9. L'utilisateur admin crée **un compte pour un client**.
10. L'utilisateur admin peut alors suivre la **procédure de prise en charge du client : devis, planification, production, facturation, relance, paiement et attestation fiscale** (service à la personne)
11. L'utilisateur admin peut a ce stade configurer les **paramètres de l'application** notamment les informations pour les devis, factures etc..

---

## Stack technique

- **Backend** : PHP 8.x, MySQL
- **Frontend** : HTML, CSS, Bootstrap 5, JavaScript, jQuery
- **API externe** : Bridge (pour liaison bancaire)
- **API externe** : Mollie (pour les liens de paiement en ligne)
- **API externe** : Payfit (pour les variables de paies)

---

## Racine du projet

- `/` : racine du projet global.

## Structure primaire du projet

- `/admin/` : racine du sous domaine admin.projet.com. C'est la que l'admin principal se connecte pour tout gérer.
- `/api/` : racine du sous domaine api.projet.com. C'est une API a usage interne avec un possibilté d'ouverture pour des partenaires.
- `/app/` : racine du sous domaine app.projet.com. C'est la que l'utilisateur se connecte pour gérer son activité et ses subalternes.
- `/cli/` : racine du sous domaine admin.projet.com. C'est la que le client se connecte pour gérer ses interventions et ses facturations.
- `/manop/` : racine du sous domaine manop.projet.com. C'est la que l'admin principal se connecte pour gérer le manuel opérationnel des interventions.
- `/pro/` : racine du sous domaine pro.projet.com. C'est la que l'intervenant se connecte pour gérer ses rapports d'activité.
- `/public/` : racine du domaine projet.com. C'est la landing page ou les services d'entretien de jardins sont présentés. C'est la que le client peut s'inscrire et se connecter.
- `/shared/` : dossier contenant les fichiers partagés entre les différents modules de l'application.
- `/sql/`: dossier contenant les fichiers de configuration .sql de l'application.
- `/vendor/` : dossier contenant les dépendances de l'application via Composer.
- `/webapp/` : racine du domaine webapp.com. C'est la landing page ou l'app de gestion est présentée. C'est la que l'utilisateur peut se connecter et gérer son activité.

## Structure secondaire des fichiers

Cette structure est à respecter pour chaque module de l'application.

- `/classes/` : dossier contenant les classes autres que 'vendor' de l'application.
- `/config/` : dossier contenant les fichiers de configuration.
- `/controllers/` : dossier contenant les contrôleurs de l'application.
- `/models/` : dossier contenant les modèles de l'application.
- `/views/` : dossier contenant les vues de l'application.
- `/public/` : dossier contenant les fichiers publics de l'application (CSS, JS, images).
- `/assets/` : dossier contenant les fichiers d'assets de l'application (images, polices, etc.).
- `/vendor/` : dossier contenant les dépendances de l'application (gérées par Composer).
- `/tests/` : dossier contenant les tests de l'application.
- `/logs/` : dossier contenant les fichiers de logs de l'application.
- `README.md` : fichier de documentation du projet.
- `LICENSE` : fichier de licence du projet.
- `composer.json` : fichier de configuration de Composer.
- `composer.lock` : fichier de verrouillage de Composer.
- `index.php` : point d'entrée de l'application.
- `.htaccess` : fichier de configuration du serveur web (Apache).
- `.gitignore` : fichier de configuration de Git pour ignorer certains fichiers et dossiers.

> Propose un fichier .py pour créer cette architecture racine/dossiers/sous-dossiers/fichiers.opt

---

## Convention MVC

- Le **contrôleur** reçoit la requête utilisateur (ex. clic, formulaire, route), traite les données, appelle les méthodes du **modèle** et transmet le résultat à une **vue**.
  - Il ne contient aucune logique métier ou SQL.
  - Il joue le rôle d’intermédiaire entre les vues et les modèles.
  - Il contrôle l’accès aux ressources (authentification, autorisation).
- Le **modèle** contient la logique métier et l'accès aux données.
  - Il gère toutes les interactions avec la base de données via `PDO` (requêtes préparées uniquement).
  - Il ne contient **aucun code HTML** ni lien avec l’affichage.
  - Il centralise les traitements métiers : calculs, règles métier, cohérence des données, validations.
- La **vue** est dédiée à l'affichage.
  - Elle ne contient **aucune requête SQL** ni logique métier.
  - Elle reçoit les données préparées par le contrôleur (tableaux, objets simples).
  - L’affichage est séparé du code grâce à un moteur de template ou du PHP propre.
  - Elle utilise systématiquement `htmlspecialchars()` pour sécuriser les sorties affichées.
- L’**architecture MVC** permet :
  - Une séparation nette des responsabilités,
  - Une meilleure lisibilité du code,
  - Une maintenance facilitée,
  - La possibilité de tester chaque couche indépendamment.
  - Une inter opérabilité entre `/app/`,`/cli/`,`/pro/`,`/admin/`,`/manop/`, notamment en ce qui concerne l'accès à la base de données et aux classes/vendor

## Sécurité

- Utiliser des requêtes SQL **préparées** avec `PDO::prepare()` pour éviter les injections SQL.
- Échapper toutes les données affichées dans les vues avec `htmlspecialchars()` ou équivalent.
- Gérer les **sessions de manière sécurisée** :
  - Démarrer les sessions uniquement dans les pages qui en ont besoin (`session_start()` dans un seul point d’entrée).
  - Protéger les routes sensibles par des vérifications (`isset($_SESSION['user_id'])` ou via middleware).
  - Regénérer les ID de session après login pour éviter les attaques de fixation de session.
- Ne jamais stocker de mots de passe en clair : toujours les hasher avec `password_hash()` et vérifier avec `password_verify()`.
- Vérifier les autorisations avant toute action utilisateur (modification/suppression de données).
- Utiliser des **tokens CSRF** pour sécuriser les formulaires critiques.
- Sessions sécurisées, vérification de l’authentification dans chaque page admin

---

## Authentification

- Gestion des utilisateurs avec rôles définis :

  - `gerant` (accès complet à tout le SaaS)
  - `responsable` (accès à la gestion quotidienne, clients, planning)
  - `intervenant` (accès restreint : planning personnel, saisie d’intervention)

  - `client` (accès limité : consultation de ses factures, devis, interventions) sur `/cli/`

- Connexion utilisateur sécurisée :

  - Les mots de passe sont hashés avec **Argon2id** via `password_hash()`
  - Vérification avec `password_verify()`
  - Protection contre les attaques par force brute (limitation des tentatives, délai de blocage)

- Déconnexion volontaire via bouton "Déconnexion" (`session_destroy()`)
- Déconnexion automatique après **inactivité prolongée** (timeout configuré, ex : 30 minutes)

  - Vérification régulière dans les contrôleurs via timestamp de dernière activité
  - Option : régénération de session après chaque activité pour limiter les risques

- Contrôle d’accès centralisé :

  - Middleware ou fonction dans un `Auth.php` (ex : `checkLogin()`, `requireRole($role)`)
  - Redirection automatique si non connecté ou rôle insuffisant
  - Affichage conditionnel dans les vues selon le rôle connecté

- Bonus sécurité :
  - Regénération d'ID de session après chaque connexion (`session_regenerate_id(true)`)
  - Optionnel : implémentation d'un **token CSRF** pour les formulaires critiques

> Les rôles doivent être vérifiés côté **contrôleur** ET éventuellement reflétés dans les **vues** (ex : menu ou actions désactivées).

---

## Règles pour le CSS et l'intégration Bootstrap

- Utilise systématiquement **Bootstrap 5** pour la mise en page, la grille, les composants et la réactivité.
- Pour toute personnalisation visuelle, crée ou modifie le fichier `/public/css/enooki.css` (ou `/assets/css/enooki.css` selon la structure du module).
- **N’écrase jamais directement les classes Bootstrap** : ajoute des classes personnalisées dans `enooki.css` et applique-les en complément.
- Dans chaque vue, assure-toi que l’ordre d’inclusion des fichiers CSS est :
  1. Bootstrap (CDN ou local)
  2. `enooki.css` (pour surcharger ou compléter Bootstrap)
- Préfère l’utilisation des utilitaires Bootstrap (`mb-3`, `text-center`, etc.) pour la majorité des besoins courants.
- Pour les couleurs, polices ou espacements spécifiques à l’entreprise, définis-les dans `enooki.css` et documente-les en commentaire dans ce fichier.
- Si tu ajoutes un composant ou une section nécessitant du CSS spécifique, documente-le brièvement dans la vue concernée (commentaire HTML) et dans `enooki.css`.
- Place toujours les fichiers CSS personnalisés dans `/assets/css/enooki.css` pour chaque module.
- Dans les vues, inclus d’abord Bootstrap, puis `/assets/css/enooki.css` :
  ```html
  <link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
  />
  <link rel="stylesheet" href="/assets/css/enooki.css" />
  ```
- N’utilise `/public/` que pour les fichiers accessibles directement à la racine du domaine (landing page, favicon, etc.).
- Pour toute nouvelle vue ou composant, indique clairement quelles classes Bootstrap sont utilisées et si des classes personnalisées sont nécessaires.
- Pour les formulaires, utilise les classes Bootstrap (`form-control`, `form-group`, etc.) pour la mise en forme et la validation.

---

## Gestion MySQL

- Chaque table **doit contenir une colonne `compte_id`** (INT NON NULL), qui identifie l’instance de succursale utilisatrice de l'application SaaS. Toutes les requêtes doivent inclure une clause `WHERE compte_id = :compte_id` pour isoler les données par client.

### Clés étrangères

- Utiliser systématiquement des **clés étrangères** (`FOREIGN KEY`) pour garantir la cohérence entre les tables.
- Les options recommandées :
  - `ON DELETE CASCADE` pour supprimer automatiquement les données liées (ex. lignes de facture quand une facture est supprimée)
  - `ON UPDATE CASCADE` si l’ID parent peut être modifié (rare mais utile)
  - `ON DELETE SET NULL` dans certains cas optionnels (ex. lien utilisateur -> manager supprimé)

### Vues MySQL

- Créer des **vues (`VIEW`)** pour exposer des données préfiltrées par `compte_id`, ou agréger des données complexes (ex. `vue_factures_totales`).
- Les vues servent aussi à simplifier les requêtes métier dans l’application (ex. dashboard, résumé d’activité).
- Nommer les vues clairement : `vue_` + nom fonctionnel (`vue_interventions_jour`).

### Triggers

- Utiliser des **triggers** avec parcimonie pour :

  - Ajouter automatiquement `compte_id` si besoin
  - Mettre à jour des champs de type `date_modification`
  - Synchroniser ou recalculer des totaux

- Exemple utile : déclencher une mise à jour de la table `factures` si une `ligne_facture` est ajoutée/supprimée.

### Bonnes pratiques

- Toutes les opérations critiques doivent être encapsulées dans des **transactions** (`BEGIN`, `COMMIT`, `ROLLBACK`) pour éviter les états partiels.
- Toujours utiliser des **requêtes préparées** côté PHP (`PDO`) pour éviter les injections SQL.
- Ajouter des **index sur `compte_id`, `user_id`, `date_...`** pour optimiser les performances des requêtes fréquentes.

> Tu peux générer des fonctions MySQL stockées (`STORED PROCEDURES`) pour automatiser des traitements récurrents côté BDD (ex. clôture mensuelle, archivage).

---

## Exemple simple de base de fichier .sql

```sql
CREATE TABLE clients (
  id INT PRIMARY KEY AUTO_INCREMENT,
  nom VARCHAR(100)
);

CREATE TABLE factures (
  id INT PRIMARY KEY AUTO_INCREMENT,
  client_id INT,
  montant DECIMAL(10,2),
  FOREIGN KEY (client_id) REFERENCES clients(id)
    ON DELETE CASCADE
    ON UPDATE CASCADE
);
```

## Exemple de requêtes MySQL

```php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $template_id = (int)$_POST['template_id'];
  $theme_id = (int)$_POST['theme_id'];
  $nom = trim($_POST['nom']);

  $stmt = $pdo->prepare("INSERT INTO fiches (template_id, theme_id, organisation_id, auteur_id, nom) VALUES (?, ?, ?, ?, ?)");
  $stmt->execute([$template_id, $theme_id, $user['organisation_id'], $user['id'], $nom]);

  $fiche_id = $pdo->lastInsertId();

  // Créer les sections basées sur le modèle
  $sections = $pdo->prepare("SELECT * FROM fiche_template_sections WHERE template_id = ? ORDER BY ordre");
  $sections->execute([$template_id]);

  foreach ($sections->fetchAll() as $s) {
    $pdo->prepare("INSERT INTO fiche_sections (fiche_id, section_id, titre, type_champ, obligatoire, ordre)
      VALUES (?, ?, ?, ?, ?, ?)")
      ->execute([$fiche_id, $s['id'], $s['titre'], $s['type_champ'], $s['obligatoire'], $s['ordre']]);
  }

  header("Location: /fiches/fill?id=$fiche_id");
  exit;
}

$templates = $pdo->query("SELECT ft.id, ft.nom, th.nom AS theme_nom, th.id AS theme_id
  FROM fiche_templates ft
  JOIN themes th ON th.id = ft.theme_id
  ORDER BY ft.nom")->fetchAll();
```

## Exemple de classe PHP modèle

```php
class MyClass
{
  private $link;
  private $idcompte;

  /**
   * __construct
   *
   * @param  mixed $idcompte
   *
   */
  public function __construct($idcompte)
  {
    $this->idcompte = $idcompte;
    $chemin = $_SERVER['DOCUMENT_ROOT'];
    $base = include $chemin . '/config/base_ionos.php';

    $host_name = $base['host_name'];
    $database = $base['database'];
    $user_name = $base['user_name'];
    $password = $base['password'];
    try {
      $this->link = new \PDO("mysql:host=$host_name; dbname=$database;", $user_name, $password);
    } catch (PDOException $e) {
      //' . $e->getMessage() . '
      echo '<p style="color:#f80">Nous avons une erreur MySQL</p><br/>';
      die();
    }
  }
  public function allRow($requete, $params = [])
  {

    try {
      $q = $this->link->prepare($requete);
      $q->execute($params);
      $variable = $q->fetchAll(PDO::FETCH_ASSOC);
      return $variable;
    } catch (PDOException $e) {
      error_log("Erreur lors de allRow() : " . $e->getMessage());
      throw new Exception("Une erreur 'fetchAll' est survenue lors de la manipulation des données.", 0, $e);
    }
  }
}

```

---

## Tests

- Pour chaque module ou fonctionnalité, propose systématiquement des exemples de tests unitaires (PHPUnit) et/ou fonctionnels.
- Indique dans quel dossier placer les fichiers de test (ex : `/tests/ClientTest.php`).
- Précise les cas de test attendus (ex : création, modification, suppression, accès non autorisé).

---

## CI/CD

- Propose des scripts et workflows adaptés pour l’intégration continue et le déploiement continu.
- Privilégie GitHub Actions (fichier `.github/workflows/ci.yml`) ou GitLab CI (`.gitlab-ci.yml`) selon le contexte du projet.
- Les workflows doivent inclure : installation des dépendances, exécution des tests, vérification du style de code, et déploiement si les tests passent.

---

## Modules de l'application `/app/`

1. Clients (adresse intervention, adresse de facturation, contact principal)
2. Devis (avec calculs TVA, remises, statuts, lien de paiement)
3. Planning (interventions, horaires, disponibilités, congés)
4. Productions (notation des heures facturable et non facturable)
5. Factures (PDF, envoi mail, paiement partiel, lien de paiement)
6. Règlements (manuels ou via API bancaire Bridge)
7. Relances (par mail automatisées)
8. Dashboard avec KPI

## Modules de l'application `/pro/`

1. Notation des productions facturables
2. Notation des productions non facturables
3. Notation des kilométrage véhicule de service
4. Vues des productions par jours, semaines, mois, année
5. Accès coordonnées client (autocomplete)
6. Signalement incident (véhicule, matériels, client...)

## Modules de l'application `/cli/`

1. Voir les devis et leurs statut
2. Voir les interventions effectuées
3. Voir les interventions prévues
4. Voir les factures et leurs statut (lien de paiement si en attente)
5. Faire une demande de devis
6. Faire une demande d'intervention
7. Documents devis, factures et attestations téléchargeables

> Ces trois modules (`/app/`, `/pro/`, `/cli/`) utilisent la même base de données, mais avec des rôles et des accès différents. Ils doivent être développés de manière à ce que les données soient accessibles uniquement aux utilisateurs autorisés.
> Ces modules feront l'objet de tests unitaires et fonctionnels pour garantir leur bon fonctionnement.

---

## Règles supplémentaires pour Copilot

- A l'issue de ta réponse fais des suggestions d'amélioration ou de validation ordonnées par exemple : "1 - je te propose de faire ceci, 2 - je te propose de faire cela, 3 - je te propose de faire cela".
- Adopte un ton enthousiaste et engageant tout en restant professionnel.
- Utilise un langage clair et accessible, sans jargon technique excessif.
- Sois proactif dans la recherche de solutions et d'améliorations.
- Propose des solutions innovantes et efficaces pour chaque problème rencontré.
- Sois attentif aux détails et à la qualité du code proposé.
- Sois ouvert aux suggestions et aux retours d'expérience.
- Sois curieux et cherche à comprendre le contexte du projet pour proposer des solutions adaptées.
- Propose toujours du code compatible avec la structure MVC décrite ci-dessus.
- Pour chaque nouvelle fonctionnalité, suggère la répartition du code entre contrôleur, modèle et vue.
- L'application utilise des url propre et un router. Propose le `.htaccess` adapté au dossier et au projet.
- Utilise systématiquement `PDO::prepare()` pour toute requête SQL.
- Pour les vues, propose du HTML avec Bootstrap 5, sans logique PHP autre que l’affichage de variables.
- Pour tout nouveau module (ex : client, facture), propose la structure des tables SQL et des classes PHP modèle associée.
- Pour les formulaires, ajoute systématiquement la protection CSRF avec bouton 'Annulation' et 'Envoi' en bas a droite.
- Pour les routes publiques et privées, indique comment vérifier l’authentification.
- Lorsque tu proposes un code, indique toujours dans quel dossier il doit être placé (ex : `/controllers/ClientController.php`).
- Propose des scripts et des workflow adaptés pour le CI/CD
- Après avoir proposé ton plan de déploiement d'un module, demande "MODIFIE" en faisant des suggestions d'amélioration ou bien demande "VALIDER" si je suis satisfait de ta proposition et que l'on peu poursuivre et déployer ton plan pour le module.
