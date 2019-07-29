# Spécifications techniques de l'implémentation du format de DRM attendues sur le portail DeclarVins

*/!\ La version de cette documentation n'est pas définitive et sera amenée à évoluer*

La spécification complète du format d'import attendu est détaillée ici : [Spécification générique DRM logiciels tiers](https://github.com/24eme/mutualisation-douane/blob/master/logiciels-tiers/). Cette documentation "générique" est commune pour les portails déclaratifs du CIVA, du CIVP, d'Interloire, d'InterRhone, d'IVBD, d'IVSO et d'IVSE.

Cette page apporte un éclairage DeclarVins à la documentation générique. Elle permet d'accéder à la liste des produits d'InterRhone, du CIVP et d'IVSE (et la manière de les déclarer) ainsi que les mouvements désirés pour la DRM DeclarVins.

## Catalogue des produits spécifiques au portail DeclarVins

Le catalogue produit nécessaire aux imports de DRM pour DeclarVins est décrit dans le fichier suivant : [Catalogue produit](catalogue_produits_declarvins.csv)

Ce fichier comporte les différentes colonnes suivantes :

1. La code certification : l'identifiant de la certification du produit
2. Le code genre : l'identifiant du genre du produit
3. Le code appellation : l'identifiant de l'appellation du produit
4. Le code mention : l'identifiant de la mention du produit
5. Le code lieu : l'identifiant du lieu du produit
6. Le code couleur : l'identifiant de la couleur du produit
7. Le code cepage : l'identifiant du cépage du produit
8. Le produit : le libellé complet du produit

La dernière colonne indique le libellé complet du produit, le processus d'import ne tiendra pas compte de ce champs si les 7 champs d'identification sont remplis. Il sera utilisé que si une ambiguité ressort de l'exploitation de ces champs.

## Catalogue des mouvements de DRM spécifiques au portail DeclarVins

Le catalogue des mouvements de DRM admis par le portail DeclarVins  [Catalogue mouvements](catalogue_mouvements_declarvins.csv) est composé de quatres colonnes :

1. Le type de DRM : « suspendu » ou « acquitte »
2. La catégorie du mouvement : stocks, entrees, sorties ou complement
3. Le type du mouvement : achat, crd, vrac, repli...
4. Un commentaire : définition du mouvement

## Libellé personnalisé

Il est possible de personnaliser les libellés des produits pour se mettre en conformité avec ceux de Prodouane, d'inclure des mentions valorisantes, de différencier des alcools à taux différents...

Pour cela, il faut obligatoirement identifier le produit via le code INAO ou le libellé fiscal (en colonne 5 ou entre parenthèse en colonne 13) 

et 

préciser le libellé personnalisé complet du produit en colonne 12 (complément) ou 13 (libellé).

## Description des lignes CRD

**Type de la CDR** : trois valeurs acceptées « PERSONNALISEES », « COLLECTIVES_DROITS_SUSPENDUS » ou « COLLECTIVES_DROITS_ACQUITTES »  
 
**Catégorie fiscale de la CRD** : trois valeurs acceptées « M » pour les vins mousseux, « T » pour les vins tranquilles ou « PI » pour les produits intermédiaires  

**Centilitrage de la CRD** : champs obligatoire, valeurs acceptées :  
 
 * CL_10 (10 cL)
 * CL_12_5 (12.5 cL)
 * CL_18_7 (18.7 cL)
 * CL_20 (20 cL)
 * CL_25 (25 cL)
 * CL_35 (35 cL)
 * CL_37_5 (37.5 cL)
 * CL_50 (50 cL)
 * CL_62 (62 cL)
 * CL_70 (70 cL)
 * CL_75 (75 cL)
 * CL_100 (1 L)
 * CL_150 (1.5 L)
 * CL_175 (1.75 L)
 * CL_200 (2 L)
 * BIB_225 (BIB 2.25 L)
 * BIB_300 (BIB 3 L)
 * BIB_400 (BIB 4 L)
 * BIB_500 (BIB 5 L)
 * BIB_800 (BIB 8 L)
 * BIB_1000 (BIB 10 L)
 
Il est possible de saisir une centilisation non présente dans la liste en respectant le format : 

(CL|BIB)_([0-9]+) : clé CL pour centilitre ou BIB pour un bib, séparateur underscore, centilisation exprimée en centilitre.

## Exemple complet de fichier d'import de DRM

Un exemple spécifique de DRM à importer pour le portail DeclarVins est disponible ici : [Exemple de fichier d'import pour DeclarVins](export_edi_complet.csv) .D

Ce fichier reprend l'ensemble des spécificités décrites ci-dessus.

## Retour d'import

Un fichier CSV contenant le résultat de l'import sera fourni en retour d'import.

Les lignes de ce fichier se constituent des champs suivants :

1. Le type de retour : « SUCCES » ou « ERREUR »
2. La catégorie de retour : « CSV » pour les données du fichier ou « ACCES » pour l'accès au webservice
3. Le numéro de ligne : En cas d'erreur, numéro de la ligne incriminée ou vide si il s'agit d'une erreur globale
4. L'identifiant d'erreur ou url de redirection DeclarVins en cas de succes
5. Description : Message décrivant le retour

La liste des erreurs (identifiant et description) pour le portail DeclarVins est disponible ici : [Liste des erreurs pour DeclarVins](liste_erreurs.csv) .

## Web Service EDI

Domaine de production : edi.declarvins.net
Domaine de test : edi-preprodv2.declarvins.net

Url d'envoi des DRM : /edi.php/edi/v2/push/etablissement/drm/id_etablissement_

Exemple de requête :

     curl -X POST -F "csv[file]=@/_drm_.csv" https://_login_:_password_@edi.declarvins.net/edi.php/edi/v2/push/etablissement/drm/_id_etablissement_

avec :

\_drm\_.csv : le chemin vers le fichier csv

\_login\_ & \_password\_ : les identifiants de connexion du compte adhérent à l'interpro.

\_id_etablissement\_ : l'identifiant interpro. de l'etablissement 

## Correspondances mouvements droits suspendus

| Mouvement | DeclarVins | CIEL Lot 2  | CIEL Lot 1 |
|-----------|------------|-------------|------------|
| stock | total_debut_mois | stock-debut-periode | stock-debut-periode |
| entrees | recolte | volume-produit | volume-produit |
| entrees | achat | achats-reintegrations | entree-droits-suspendus |
| entrees temporaires | embouteillage | embouteillage | travail-a-facon |
| entrees temporaires | mouvement | relogement | autres-entrees |
| entrees temporaires | travail | travail-a-facon | travail-a-facon |
| entrees temporaires | distillation | distillation-a-facon | volume-produit |
| entrees internes | repli + declassement | replis-declassement-transfert-changement-appellation | autres-entrees |
| entrees internes | manipulation | manipulations | volume-produit |
| entrees internes | vci | integration-vci-agree | X |
| entrees | excedent | autres-entrees | autres-entrees |
| entrees | crd | replacement-suspension/volume | replacement-suspension/volume |
| sorties | factures + crd | ventes-france-crd-suspendus | sorties-avec-paiement-annee-courante |
| sorties | crd_acquittes | ventes-france-crd-acquittes | autres-sorties |
| sorties sans droits | vrac + lies + export | sorties-definitives | sorties-definitives + lies-vins-distilles |
| sorties sans droits | consommation | consommation-familiale-degustation | sorties-exoneration-droits |
| sorties sans droits temporaires | embouteillage | embouteillage | travail-a-facon |
| sorties sans droits temporaires | mouvement | relogement | autres-sorties |
| sorties sans droits temporaires | travail | travail-a-facon | travail-a-facon |
| sorties sans droits temporaires | distillation | distillation-a-facon | fabrication-autre-produit |
| sorties sans droits internes | repli + declassement | replis-declassement-transfert-changement-appellation | autres-sorties |
| sorties sans droits internes | mutage | fabrication-autre-produit | fabrication-autre-produit |
| sorties sans droits internes | vci | revendication-vci | X |
| sorties sans droits internes | autres_interne | autres-mouvements-internes | autres-sorties |
| sorties sans droits | autres + pertes | autres-sorties | autres-sorties |
| stocks | total | stock-fin-periode | stock-fin-periode |

## Correspondances mouvements droits acquittes

| Mouvement | DeclarVins | CIEL Lot 2  | CIEL Lot 1 |
|-----------|------------|-------------|------------|
| stocks | total_debut_mois | stock-debut-periode | stock-debut-periode |
| entrees | achat | achats | achats |
| entrees | autres | autres-entrees | autres-entrees |
| sorties | crd | ventes | ventes |
| sorties | replacement | replacement-suspension | replacement-suspension |
| sorties | autres | autres-sorties | autres-sorties |
| stocks | total | stock-fin-periode | stock-fin-periode |

## Suivi du projet chez les éditeurs de registres de cave 

| Nom de l'Éditeur | Prise de contact | Génération du fichier de transfer | Recette des échanges en préproduction | Transmission opérationnelle en production | Versions compatibles |
|------------------|------------------|-----------------------------------|---------------------------------------|------------------------------------------------------|----------------------|
| CR2i             | Oui |      |  |  |  |
| ISAGRI           | Oui | Oui  | Oui |  |  |
| ICS-SUD          | Oui |      |  |  |  |
| I3S              | Oui |      |  |  |  |
| Antislash        | Oui | Oui  | Oui | Oui |  |
| La Graine Informatique | Oui |      |  |  |  |
