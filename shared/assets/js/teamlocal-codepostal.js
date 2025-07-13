/**
 * Gestion de la recherche d'équipe locale par code postal
 * Version simplifiée sans géolocalisation
 */

// Données des secteurs par code postal
const secteursParCodePostal = {

    '33380': { secteur: 'bordeaux', nom: 'Mios' }, // Mios
'33470': { secteur: 'bordeaux', nom: 'Gujan-Mestras' }, // Gujan-Mestras
'33460': { secteur: 'bordeaux', nom: 'Arès' }, // Arès
'33450': { secteur: 'bordeaux', nom: 'Saint-Loubès' }, // Saint-Loubès
'33440': { secteur: 'bordeaux', nom: 'Ambarès-et-Lagrave' }, // Ambarès-et-Lagrave
'33430': { secteur: 'bordeaux', nom: 'Bazas' }, // Bazas
'33420': { secteur: 'bordeaux', nom: 'Branne' }, // Branne
'33410': { secteur: 'bordeaux', nom: 'Cadillac' }, // Cadillac
'33400': { secteur: 'bordeaux', nom: 'Talence' }, // Talence
'33390': { secteur: 'bordeaux', nom: 'Blaye' }, // Blaye
'33360': { secteur: 'bordeaux', nom: 'Latresne' }, // Latresne
'33350': { secteur: 'bordeaux', nom: 'Castillon-la-Bataille' }, // Castillon-la-Bataille
'33330': { secteur: 'bordeaux', nom: 'Saint-Émilion' }, // Saint-Émilion
'33320': { secteur: 'bordeaux', nom: 'Eysines' }, // Eysines
'33310': { secteur: 'bordeaux', nom: 'Lormont' }, // Lormont
'33300': { secteur: 'bordeaux', nom: 'Bordeaux' }, // Bordeaux
'33290': { secteur: 'bordeaux', nom: 'Blanquefort' }, // Blanquefort
'33270': { secteur: 'bordeaux', nom: 'Bouliac' }, // Bouliac
'33260': { secteur: 'bordeaux', nom: 'La Teste-de-Buch' }, // La Teste-de-Buch
'33250': { secteur: 'bordeaux', nom: 'Cissac-Médoc' }, // Cissac-Médoc
'33240': { secteur: 'bordeaux', nom: 'Saint-André-de-Cubzac' }, // Saint-André-de-Cubzac
'33230': { secteur: 'bordeaux', nom: 'Coutras' }, // Coutras
'33220': { secteur: 'bordeaux', nom: 'Sainte-Foy-la-Grande' }, // Sainte-Foy-la-Grande
'33210': { secteur: 'bordeaux', nom: 'Langon' }, // Langon
'33200': { secteur: 'bordeaux', nom: 'Bordeaux Caudéran' }, // Bordeaux Caudéran
    // Secteur EJERI Jardins - Orléans (Lailly-en-Val, Lamotte-Beuvron et 25km autour)
    // Loiret (45)
    '45000': { secteur: 'orleans', nom: 'Orléans' }, // Orléans
    '45100': { secteur: 'orleans', nom: 'Orléans-la-Source' }, // Orléans-la-Source
    '45120': { secteur: 'orleans', nom: 'Châlette-sur-Loing' }, // Châlette-sur-Loing
    '45140': { secteur: 'orleans', nom: 'Ingré' }, // Ingré
    '45160': { secteur: 'orleans', nom: 'Olivet' }, // Olivet
    '45170': { secteur: 'orleans', nom: 'Neuville-aux-Bois' }, // Neuville-aux-Bois
    '45190': { secteur: 'orleans', nom: 'Beaugency' }, // Beaugency
    '45200': { secteur: 'orleans', nom: 'Amilly' }, // Amilly
    '45210': { secteur: 'orleans', nom: 'Ferrières-en-Gâtinais' }, // Ferrières-en-Gâtinais
    '45220': { secteur: 'orleans', nom: 'Château-Renard' }, // Château-Renard
    '45230': { secteur: 'orleans', nom: 'Lailly-en-Val' }, // Lailly-en-Val
    '45240': { secteur: 'orleans', nom: 'La Ferté-Saint-Aubin' }, // La Ferté-Saint-Aubin
    '45250': { secteur: 'orleans', nom: 'Briare' }, // Briare
    '45260': { secteur: 'orleans', nom: 'Lorris' }, // Lorris
    '45270': { secteur: 'orleans', nom: 'Bellegarde' }, // Bellegarde
    '45290': { secteur: 'orleans', nom: 'Nogent-sur-Vernisson' }, // Nogent-sur-Vernisson
    '45300': { secteur: 'orleans', nom: 'Pithiviers' }, // Pithiviers
    '45320': { secteur: 'orleans', nom: 'Courtenay' }, // Courtenay
    '45330': { secteur: 'orleans', nom: 'Malesherbes' }, // Malesherbes
    '45340': { secteur: 'orleans', nom: 'Beaune-la-Rolande' }, // Beaune-la-Rolande
    '45350': { secteur: 'orleans', nom: 'Cortrat' }, // Cortrat
    '45360': { secteur: 'orleans', nom: 'Châtillon-sur-Loire' }, // Châtillon-sur-Loire
    '45370': { secteur: 'orleans', nom: 'Cléry-Saint-André' }, // Cléry-Saint-André
    '45380': { secteur: 'orleans', nom: 'La Chapelle-Saint-Mesmin' }, // La Chapelle-Saint-Mesmin
    '45390': { secteur: 'orleans', nom: 'Puiseaux' }, // Puiseaux
    '45400': { secteur: 'orleans', nom: 'Fleury-les-Aubrais' }, // Fleury-les-Aubrais
    '45410': { secteur: 'orleans', nom: 'Artenay' }, // Artenay
    '45420': { secteur: 'orleans', nom: 'Bonny-sur-Loire' }, // Bonny-sur-Loire
    '45430': { secteur: 'orleans', nom: 'Chécy' }, // Chécy
    '45450': { secteur: 'orleans', nom: 'Fay-aux-Loges' }, // Fay-aux-Loges
    '45460': { secteur: 'orleans', nom: 'Les Bordes' }, // Les Bordes
    '45470': { secteur: 'orleans', nom: 'Loury' }, // Loury
    '45480': { secteur: 'orleans', nom: 'Pont-Saint-Esprit' }, // Pont-Saint-Esprit
    '45490': { secteur: 'orleans', nom: 'Corbeilles' }, // Corbeilles
    '45500': { secteur: 'orleans', nom: 'Gien' }, // Gien
    '45510': { secteur: 'orleans', nom: 'Tigy' }, // Tigy
    '45520': { secteur: 'orleans', nom: 'Chevilly' }, // Chevilly
    '45530': { secteur: 'orleans', nom: 'Vitry-aux-Loges' }, // Vitry-aux-Loges
    '45540': { secteur: 'orleans', nom: 'Montargis' }, // Montargis
    '45550': { secteur: 'orleans', nom: 'Saint-Denis-de-l\'Hôtel' }, // Saint-Denis-de-l'Hôtel
    '45560': { secteur: 'orleans', nom: 'Saint-Denis-en-Val' }, // Saint-Denis-en-Val
    '45570': { secteur: 'orleans', nom: 'Demigny' }, // Demigny
    '45600': { secteur: 'orleans', nom: 'Sully-sur-Loire' }, // Sully-sur-Loire
    '45700': { secteur: 'orleans', nom: 'Villemandeur' }, // Villemandeur
    '45740': { secteur: 'orleans', nom: 'Lailly-en-Val' }, // Lailly-en-Val
    '45750': { secteur: 'orleans', nom: 'Saint-Pryvé-Saint-Mesmin' }, // Saint-Pryvé-Saint-Mesmin
    '45800': { secteur: 'orleans', nom: 'Saint-Jean-de-Braye' }, // Saint-Jean-de-Braye
    
    // Loir-et-Cher (41) - Secteur Lamotte-Beuvron
    '41210': { secteur: 'orleans', nom: 'Neung-sur-Beuvron' }, // Neung-sur-Beuvron
    '41230': { secteur: 'orleans', nom: 'Soings-en-Sologne' }, // Soings-en-Sologne
    '41250': { secteur: 'orleans', nom: 'Bracieux' }, // Bracieux
    '41300': { secteur: 'orleans', nom: 'Salbris' }, // Salbris
    '41350': { secteur: 'orleans', nom: 'Huisseau-sur-Cosson' },
    '41500': { secteur: 'orleans', nom: 'Mer' }, // Mer
    '41600': { secteur: 'orleans', nom: 'Lamotte-Beuvron' }, // Lamotte-Beuvron
    
    // Secteur EJERI Jardins - Nantes (Cugand, Cholet, La Roche-sur-Yon et 25km autour)
    // Vendée (85)
    '85000': { secteur: 'nantes', nom: 'La Roche-sur-Yon' }, // La Roche-sur-Yon
    '85100': { secteur: 'nantes', nom: 'Les Sables-d\'Olonne' }, // Les Sables-d'Olonne
    '85110': { secteur: 'nantes', nom: 'Chantonnay' }, // Chantonnay
    '85120': { secteur: 'nantes', nom: 'La Châtaigneraie' }, // La Châtaigneraie
    '85130': { secteur: 'nantes', nom: 'Tiffauges' }, // Tiffauges
    '85140': { secteur: 'nantes', nom: 'Essarts-en-Bocage' }, // Essarts-en-Bocage
    '85150': { secteur: 'nantes', nom: 'Landeronde' }, // Landeronde
    '85160': { secteur: 'nantes', nom: 'Saint-Jean-de-Monts' }, // Saint-Jean-de-Monts
    '85170': { secteur: 'nantes', nom: 'Bellevigny' }, // Bellevigny
    '85180': { secteur: 'nantes', nom: 'Château-d\'Olonne' }, // Château-d'Olonne
    '85190': { secteur: 'nantes', nom: 'Aizenay' }, // Aizenay
    '85200': { secteur: 'nantes', nom: 'Fontenay-le-Comte' }, // Fontenay-le-Comte
    '85210': { secteur: 'nantes', nom: 'Sainte-Hermine' }, // Sainte-Hermine
    '85220': { secteur: 'nantes', nom: 'Coëx' }, // Coëx
    '85230': { secteur: 'nantes', nom: 'Bouin' }, // Bouin
    '85240': { secteur: 'nantes', nom: 'Nieul-sur-l\'Autise' }, // Nieul-sur-l'Autise
    '85250': { secteur: 'nantes', nom: 'Saint-Fulgent' }, // Saint-Fulgent
    '85260': { secteur: 'nantes', nom: 'L\'Herbergement' }, // L'Herbergement
    '85270': { secteur: 'nantes', nom: 'Saint-Hilaire-de-Riez' }, // Saint-Hilaire-de-Riez
    '85280': { secteur: 'nantes', nom: 'La Ferrière' }, // La Ferrière
    '85290': { secteur: 'nantes', nom: 'Mortagne-sur-Sèvre' }, // Mortagne-sur-Sèvre
    '85300': { secteur: 'nantes', nom: 'Challans' }, // Challans
    '85310': { secteur: 'nantes', nom: 'La Chaize-le-Vicomte' }, // La Chaize-le-Vicomte
    '85320': { secteur: 'nantes', nom: 'Mareuil-sur-Lay-Dissais' }, // Mareuil-sur-Lay-Dissais
    '85330': { secteur: 'nantes', nom: 'Noirmoutier-en-l\'Île' }, // Noirmoutier-en-l'Île
    '85340': { secteur: 'nantes', nom: 'Olonne-sur-Mer' }, // Olonne-sur-Mer
    '85350': { secteur: 'nantes', nom: 'L\'Île-d\'Yeu' }, // L'Île-d'Yeu
    '85360': { secteur: 'nantes', nom: 'La Tranche-sur-Mer' }, // La Tranche-sur-Mer
    '85400': { secteur: 'nantes', nom: 'Luçon' }, // Luçon
    '85440': { secteur: 'nantes', nom: 'Talmont-Saint-Hilaire' }, // Talmont-Saint-Hilaire
    '85500': { secteur: 'nantes', nom: 'Les Herbiers' }, // Les Herbiers
    '85600': { secteur: 'nantes', nom: 'Montaigu-Vendée' }, // Montaigu-Vendée
    '85700': { secteur: 'nantes', nom: 'Pouzauges' }, // Pouzauges
    '85800': { secteur: 'nantes', nom: 'Saint-Gilles-Croix-de-Vie' }, // Saint-Gilles-Croix-de-Vie
    
    // Maine-et-Loire (49) - Secteur Cholet
    '49000': { secteur: 'nantes', nom: 'Angers' }, // Angers
    '49100': { secteur: 'nantes', nom: 'Angers' }, // Angers
    '49300': { secteur: 'nantes', nom: 'Cholet' }, // Cholet
    '49310': { secteur: 'nantes', nom: 'Vihiers' }, // Vihiers
    '49320': { secteur: 'nantes', nom: 'Brissac-Quincé' }, // Brissac-Quincé
    '49330': { secteur: 'nantes', nom: 'Châteauneuf-sur-Sarthe' }, // Châteauneuf-sur-Sarthe
    '49340': { secteur: 'nantes', nom: 'Trémentines' }, // Trémentines
    '49350': { secteur: 'nantes', nom: 'Gennes-Val-de-Loire' }, // Gennes-Val-de-Loire
    '49360': { secteur: 'nantes', nom: 'Ouzilly-Vignolles' }, // Ouzilly-Vignolles
    '49370': { secteur: 'nantes', nom: 'Bécon-les-Granits' }, // Bécon-les-Granits
    '49380': { secteur: 'nantes', nom: 'Faye-d\'Anjou' }, // Faye-d'Anjou
    '49390': { secteur: 'nantes', nom: 'Vernantes' }, // Vernantes
    '49400': { secteur: 'nantes', nom: 'Saumur' }, // Saumur
    '49410': { secteur: 'nantes', nom: 'Mauges-sur-Loire' }, // Mauges-sur-Loire
    '49420': { secteur: 'nantes', nom: 'Pouancé' }, // Pouancé
    '49430': { secteur: 'nantes', nom: 'Durtal' }, // Durtal
    '49440': { secteur: 'nantes', nom: 'Candé' }, // Candé
    '49450': { secteur: 'nantes', nom: 'Sèvremoine' }, // Sèvremoine
    '49460': { secteur: 'nantes', nom: 'Montreuil-Juigné' }, // Montreuil-Juigné
    '49470': { secteur: 'nantes', nom: 'Montreuil-Bellay' }, // Montreuil-Bellay
    '49500': { secteur: 'nantes', nom: 'Segré-en-Anjou Bleu' }, // Segré-en-Anjou Bleu
    '49600': { secteur: 'nantes', nom: 'Beaupréau-en-Mauges' }, // Beaupréau-en-Mauges
    '49700': { secteur: 'nantes', nom: 'Doué-en-Anjou' }, // Doué-en-Anjou
    '49800': { secteur: 'nantes', nom: 'Trélazé' }, // Trélazé

    // Loire-Atlantique (44) - Secteur Cugand et proche Nantes
    '44000': { secteur: 'nantes', nom: 'Nantes' }, // Nantes
    '44100': { secteur: 'nantes', nom: 'Nantes' }, // Nantes
    '44110': { secteur: 'nantes', nom: 'Châteaubriant' }, // Châteaubriant
    '44120': { secteur: 'nantes', nom: 'Vertou' }, // Vertou
    '44130': { secteur: 'nantes', nom: 'Blain' }, // Blain
    '44140': { secteur: 'nantes', nom: 'Geneston' }, // Geneston
    '44150': { secteur: 'nantes', nom: 'Ancenis-Saint-Géréon' }, // Ancenis-Saint-Géréon
    '44160': { secteur: 'nantes', nom: 'Pontchâteau' }, // Pontchâteau
    '44170': { secteur: 'nantes', nom: 'Nozay' }, // Nozay
    '44190': { secteur: 'nantes', nom: 'Clisson' }, // Clisson
    '44200': { secteur: 'nantes', nom: 'Nantes' }, // Nantes
    '44210': { secteur: 'nantes', nom: 'Pornic' }, // Pornic
    '44220': { secteur: 'nantes', nom: 'Couëron' }, // Couëron
    '44230': { secteur: 'nantes', nom: 'Saint-Sébastien-sur-Loire' }, // Saint-Sébastien-sur-Loire
    '44240': { secteur: 'nantes', nom: 'La Chapelle-sur-Erdre' }, // La Chapelle-sur-Erdre
    '44250': { secteur: 'nantes', nom: 'Saint-Brevin-les-Pins' }, // Saint-Brevin-les-Pins
    '44260': { secteur: 'nantes', nom: 'Savenay' }, // Savenay
    '44270': { secteur: 'nantes', nom: 'Machecoul-Saint-Même' }, // Machecoul-Saint-Même
    '44280': { secteur: 'nantes', nom: 'Sautron' }, // Sautron
    '44290': { secteur: 'nantes', nom: 'Guémené-Penfao' }, // Guémené-Penfao
    '44300': { secteur: 'nantes', nom: 'Nantes' }, // Nantes
    '44310': { secteur: 'nantes', nom: 'Saint-Philbert-de-Grand-Lieu' }, // Saint-Philbert-de-Grand-Lieu
    '44320': { secteur: 'nantes', nom: 'Chaumes-en-Retz' }, // Chaumes-en-Retz
    '44330': { secteur: 'nantes', nom: 'Vallet' }, // Vallet
    '44340': { secteur: 'nantes', nom: 'Bouguenais' }, // Bouguenais
    '44350': { secteur: 'nantes', nom: 'Guérande' }, // Guérande
    '44360': { secteur: 'nantes', nom: 'Vigneux-de-Bretagne' }, // Vigneux-de-Bretagne
    '44370': { secteur: 'nantes', nom: 'Varades' }, // Varades
    '44380': { secteur: 'nantes', nom: 'Pornichet' }, // Pornichet
    '44390': { secteur: 'nantes', nom: 'Saffré' }, // Saffré
    '44400': { secteur: 'nantes', nom: 'Rezé' }, // Rezé
    '44410': { secteur: 'nantes', nom: 'Herbignac' }, // Herbignac
    '44420': { secteur: 'nantes', nom: 'La Turballe' }, // La Turballe
    '44430': { secteur: 'nantes', nom: 'Le Loroux-Bottereau' }, // Le Loroux-Bottereau
    '44440': { secteur: 'nantes', nom: 'Riaillé' }, // Riaillé
    '44450': { secteur: 'nantes', nom: 'Saint-Julien-de-Concelles' }, // Saint-Julien-de-Concelles
    '44460': { secteur: 'nantes', nom: 'Avessac' }, // Avessac
    '44470': { secteur: 'nantes', nom: 'Carquefou' }, // Carquefou
    '44480': { secteur: 'nantes', nom: 'Donges' }, // Donges
    '44490': { secteur: 'nantes', nom: 'Le Croisic' }, // Le Croisic
    '44500': { secteur: 'nantes', nom: 'La Baule-Escoublac' }, // La Baule-Escoublac
    '44510': { secteur: 'nantes', nom: 'Le Pouliguen' }, // Le Pouliguen
    '44520': { secteur: 'nantes', nom: 'La Meilleraye-de-Bretagne' }, // La Meilleraye-de-Bretagne
    '44530': { secteur: 'nantes', nom: 'Guenrouet' }, // Guenrouet
    '44540': { secteur: 'nantes', nom: 'Saint-Mars-la-Jaille' }, // Saint-Mars-la-Jaille
    '44550': { secteur: 'nantes', nom: 'Montoir-de-Bretagne' }, // Montoir-de-Bretagne
    '44560': { secteur: 'nantes', nom: 'Paimbœuf' }, // Paimbœuf
    '44570': { secteur: 'nantes', nom: 'Trignac' }, // Trignac
    '44580': { secteur: 'nantes', nom: 'Bourgneuf-en-Retz' }, // Bourgneuf-en-Retz
    '44590': { secteur: 'nantes', nom: 'Sion-les-Mines' }, // Sion-les-Mines
    '44600': { secteur: 'nantes', nom: 'Saint-Nazaire' }, // Saint-Nazaire
    '44610': { secteur: 'nantes', nom: 'Indre' }, // Indre
    '44620': { secteur: 'nantes', nom: 'La Montagne' }, // La Montagne
    '44630': { secteur: 'nantes', nom: 'Plesse' }, // Plesse
    '44640': { secteur: 'nantes', nom: 'Le Pellerin' }, // Le Pellerin
    '44650': { secteur: 'nantes', nom: 'Corcoué-sur-Logne' }, // Corcoué-sur-Logne
    '44660': { secteur: 'nantes', nom: 'Treillières' }, // Treillières
    '44670': { secteur: 'nantes', nom: 'Ancenis-Saint-Géréon' }, // Ancenis-Saint-Géréon
    '44680': { secteur: 'nantes', nom: 'Sainte-Pazanne' }, // Sainte-Pazanne
    '44690': { secteur: 'nantes', nom: 'Château-Thébaud' }, // Château-Thébaud
    '44700': { secteur: 'nantes', nom: 'Orvault' }, // Orvault
    '44710': { secteur: 'nantes', nom: 'Port-Saint-Père' }, // Port-Saint-Père
    '44720': { secteur: 'nantes', nom: 'Saint-Joachim' }, // Saint-Joachim
    '44730': { secteur: 'nantes', nom: 'Saint-Michel-Chef-Chef' }, // Saint-Michel-Chef-Chef
    '44740': { secteur: 'nantes', nom: 'Batz-sur-Mer' }, // Batz-sur-Mer
    '44750': { secteur: 'nantes', nom: 'Quilly' }, // Quilly
    '44760': { secteur: 'nantes', nom: 'Les Moutiers-en-Retz' }, // Les Moutiers-en-Retz
    '44770': { secteur: 'nantes', nom: 'La Plaine-sur-Mer' }, // La Plaine-sur-Mer
    '44780': { secteur: 'nantes', nom: 'Missillac' }, // Missillac
    '44790': { secteur: 'nantes', nom: 'Les Sorinières' }, // Les Sorinières
    '44800': { secteur: 'nantes', nom: 'Saint-Herblain' }, // Saint-Herblain
    '44810': { secteur: 'nantes', nom: 'Héric' }, // Héric
    '44820': { secteur: 'nantes', nom: 'Erbrée' }, // Erbrée
    '44830': { secteur: 'nantes', nom: 'Bouaye' }, // Bouaye
    '44840': { secteur: 'nantes', nom: 'Les Sorinières' }, // Les Sorinières
    '44850': { secteur: 'nantes', nom: 'Ligne' }, // Ligne
    '44860': { secteur: 'nantes', nom: 'Saint-Aignan-Grandlieu' }, // Saint-Aignan-Grandlieu
    '44870': { secteur: 'nantes', nom: 'Machecoul-Saint-Même' }, // Machecoul-Saint-Même
    '44880': { secteur: 'nantes', nom: 'Sautron' }, // Sautron
    '44890': { secteur: 'nantes', nom: 'Château-Thébaud' }, // Château-Thébaud
    '44900': { secteur: 'nantes', nom: 'Nantes' }, // Nantes
    
    // Secteurs par défaut pour autres codes postaux
    'default': { secteur: 'general', nom: 'Équipe Générale' }
};

// Données des équipes par secteur
const equipesParSecteur = {
    'orleans': {
        nom: 'EJERI Jardins - Val de Loire',
        description: 'Équipe spécialisée dans l\'entretien de jardins en région Centre-Val de Loire',
        contact: {
            nom: 'François',
            telephone: '02 38 45 15 78',
            email: 'francois@ejeri.fr',
            adresse: '12 Rue de la République, 45000 Orléans'
        },
        zone: 'Beaugency et un rayon de 25km (Loiret 45, Loir-et-Cher 41)',
        specialites: ['Jardins privés', 'Espaces verts', 'Taille et élagage', 'Entretien saisonnier'],
        image: 'assets/img/enooki_photos/beaugency.png'
    },
    'bordeaux': {
        nom: 'EJERI Jardins - Mios',
        description: 'Équipe spécialisée dans l\'entretien de jardins en région Nouvelle-Aquitaine',
        contact: {
            nom: 'Loïc',
            telephone: '06 98 40 52 08',
            email: 'loic@coteservice.fr',
            adresse: '18C Route des Navarries 33380 Mios'
        },
        zone: 'Mios et un rayon de 25km (Gironde 33)',
        specialites: ['Jardins privés', 'Espaces verts', 'Taille et élagage', 'Entretien saisonnier'],
        image: 'assets/img/enooki_photos/mios.png'
    },
    'nantes': {
        nom: 'EJERI Jardins - Nantes',
        description: 'Équipe spécialisée dans l\'entretien de jardins en région Pays de la Loire',
        contact: {
            nom: 'Renaud',
            telephone: '06 59 23 80 28',
            email: 'renaud@ejeri.fr',
            adresse: '15 Boulevard de la Prairie, 44000 Nantes'
        },
        zone: 'Nantes et un rayon de 25km (Loire-Atlantique 44, Maine-et-Loire 49, Vendée 85)',
        specialites: ['Jardins côtiers', 'Grands espaces verts', 'Tonte et débroussaillage', 'Jardins de prestige'],
        image: 'assets/img/team/team-nantes.jpg'
    },
    'general': {
        nom: 'Équipe hors zone',
        description: 'Notre équipe principale pour tous types d\'interventions',
        contact: {
            nom: 'Service Client EJERI',
            telephone: 'François au 02 38 45 15 78',
            email: 'francois@ejeri.fr',
            adresse: '3 place de l\'Eglise, 45740 Lailly-en-Val'
        },
        zone: 'Toute la France et régions limitrophes',
        specialites: ['Tous types de jardins', 'Devis gratuit', 'Intervention rapide'],
        image: 'assets/img/team/team-general.jpg'
    }
};

// Fonction pour rechercher l'équipe par code postal
function rechercherEquipeParCodePostal(codePostal) {
    // Nettoyer le code postal
    codePostal = codePostal.trim().replace(/\s/g, '');
    
    // Validation du format
    if (!/^\d{5}$/.test(codePostal)) {
        afficherErreur('Veuillez saisir un code postal valide (5 chiffres)');
        return;
    }
    
    // Rechercher le secteur
    let secteurInfo = secteursParCodePostal[codePostal];
    
    // Si code postal non trouvé, utiliser le secteur par défaut
    if (!secteurInfo) {
        secteurInfo = secteursParCodePostal['default'];
    }
    
    // Récupérer les données de l'équipe
    const equipe = equipesParSecteur[secteurInfo.secteur];
    
    // Fallback vers l'équipe générale si aucune équipe trouvée
    const equipeFinale = equipe || equipesParSecteur['general'];
    
    if (equipeFinale) {
        afficherEquipe(equipeFinale, codePostal);
    } else {
        afficherErreur('Aucune équipe trouvée pour ce secteur');
    }
}

// Fonction pour afficher l'équipe
function afficherEquipe(equipe, codePostal) {
    const teamSecteur = document.getElementById('team-secteur');
    const teamSec = document.getElementById('team-sec');
    const adrSec = document.getElementById('secteur-adresse');
    const cpSec = document.getElementById('cp_form');
    //<h6><i class="bi bi-geo-alt text-primary me-2"></i></h6>
    const cp = codePostal;
    const ht =`
    <div class="text-center mb-3">
    
    <p class="small" style="width:100vh">${equipe.nom}<br>
    ${equipe.contact.telephone}<br>
    ${equipe.contact.email}
    </p>
    </div>`;

    const html = `
        <!--<div class="col-lg-8 mx-auto">
            <div class="card border-0 myshadow">
                <div class="card-body p-4">
                    <div class="row">
                        <div class="col-md-4 text-center">
                            <img src="${equipe.image}" alt="${equipe.nom}" class="img-fluid rounded-circle mb-3" style="width: 150px; height: 150px; object-fit: cover;">
                        </div>
                        <div class="col-md-8">
                            <h4 class="text-primary">${equipe.nom}</h4>
                            <p class="text-muted">${equipe.description}</p>
                            
                            <div class="mb-3">
                                <h6><i class="bi bi-geo-alt text-primary me-2"></i>Zone d'intervention</h6>
                                <p class="small text-muted">${equipe.zone}</p>
                            </div>
                            
                            <div class="mb-3">
                                <h6><i class="bi bi-star text-primary me-2"></i>Spécialités</h6>
                                <div class="d-flex flex-wrap gap-1">
                                    ${equipe.specialites.map(spec => `<span class="badge bg-light text-dark">${spec}</span>`).join('')}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>-->
        
        <div class="col-lg-4 mx-md-auto text-center">
            <div class="card border-0 myshadow h-100">
             <img src="${equipe.image}" class="card-img-top" alt="Intervention Journée, demi-journée" />
                <div class="card-body">
                    <h5 class="card-title text-primary">
                        <i class="bi bi-person-lines-fill me-2"></i>Votre contact
                    </h5>
                    
                    <div class="contact-info">
                        <div class="mb-3">
                            <strong>${equipe.contact.nom}</strong>
                        </div>
                        
                        <div class="mb-2">
                            <i class="bi bi-telephone text-primary me-2"></i>
                            <a href="tel:${equipe.contact.telephone.replace(/\s/g, '')}" class="text-decoration-none">
                                ${equipe.contact.telephone}
                            </a>
                        </div>
                        
                        <div class="mb-2">
                            <i class="bi bi-envelope text-primary me-2"></i>
                            <a href="mailto:${equipe.contact.email}" class="text-decoration-none">
                                ${equipe.contact.email}
                            </a>
                        </div>
                        
                      <!---  <div class="mb-3">
                            <i class="bi bi-geo-alt text-primary me-2"></i>
                            <span class="small">${equipe.contact.adresse}</span>
                        </div>--->
                        
                        <hr>
                        
                        <div class="text-center">
                            <button class="btn btn-primary btn-sm me-2" onclick="demanderDevis('${codePostal}')">
                                <i class="bi bi-calculator"></i> Devis gratuit
                            </button>
                            <button class="btn btn-outline-primary btn-sm" onclick="ouvrirContact('${equipe.contact.telephone}')">
                                <i class="bi bi-telephone"></i> Appeler
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;
    cpSec.innerHTML = cp;
    teamSecteur.innerHTML = html;
    adrSec.innerHTML = html;
    teamSec.innerHTML = ht;
    
    // Animation d'apparition
    teamSecteur.style.opacity = '0';
    setTimeout(() => {
        teamSecteur.style.transition = 'opacity 0.3s ease-in-out';
        teamSecteur.style.opacity = '1';
    }, 100);
    teamSec.style.opacity = '0';
    setTimeout(() => {
        teamSec.style.transition = 'opacity 0.3s ease-in-out';
        teamSec.style.opacity = '1';
    }, 100);
}

// Fonction pour afficher une erreur
function afficherErreur(message) {
    const teamSecteur = document.getElementById('team-secteur');
    const teamSec = document.getElementById('team-sec');
    const ht = `
    <div class="col-12 text-center">
       
              
                <p class="text-warning">${message}</p>
               
            
    </div>
`;
    const html = `
        <div class="col-12 text-center">
            <div class="card border-0 myshadow">
                <div class="card-body py-4">
                    <i class="bi bi-exclamation-triangle text-warning mb-3" style="font-size: 3rem;"></i>
                    <h5 class="text-warning">${message}</h5>
                    <p class="text-muted">Veuillez réessayer avec un autre code postal</p>
                </div>
            </div>
        </div>
    `;
    
    teamSecteur.innerHTML = html;
    teamSec.innerHTML = ht;
}

// Fonction pour demander un devis
function demanderDevis(codePostal) {
    // Rediriger vers le formulaire de contact avec le code postal pré-rempli
    const contactSection = document.getElementById('contact');
    if (contactSection) {
        // Pré-remplir le code postal dans le formulaire de contact si disponible
        const codePostalField = document.querySelector('#contact input[name="code_postal"]');
        if (codePostalField) {
            codePostalField.value = codePostal;
        }
        
        // Scroll vers la section contact
        contactSection.scrollIntoView({ behavior: 'smooth' });
    }
}

// Fonction pour ouvrir l'appel téléphonique
function ouvrirContact(telephone) {
    window.location.href = `tel:${telephone.replace(/\s/g, '')}`;
}

// Initialisation au chargement de la page
document.addEventListener('DOMContentLoaded', function() {
    const codePostalInput = document.getElementById('code-postal');
    const btnRechercher = document.getElementById('btn-rechercher');
    
    // Gestion du clic sur le bouton rechercher
    if (btnRechercher) {
        btnRechercher.addEventListener('click', function() {
            const codePostal = codePostalInput.value;
            rechercherEquipeParCodePostal(codePostal);
        });
    }
    
    // Gestion de la touche Entrée dans le champ
    if (codePostalInput) {
        codePostalInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                rechercherEquipeParCodePostal(this.value);
            }
        });
        
        // Validation en temps réel
        codePostalInput.addEventListener('input', function() {
            // Limiter à 5 chiffres
            this.value = this.value.replace(/\D/g, '').substring(0, 5);
        });
    }
});
