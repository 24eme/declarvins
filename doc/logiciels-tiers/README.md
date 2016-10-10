#Spécifications techniques de l'implémentation du format de DRM attendues sur le portail DeclarVins

**/!\ La version de cette documentation n'est pas définitive et sera amenée à évoluer /!\**

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

1. Le type de DRM : suspendu ou acquitte
2. La catégorie du mouvement : stocks, entrees, sorties ou complement
3. Le type du mouvement : achat, crd, vrac, repli...
4. Un commentaire : définition du mouvement

##Description des lignes CRD

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

## Exemple complet de fichier d'import de DRM

Un exemple spécifique de DRM à importer pour le portail DeclarVins est disponible ici : [Exemple de fichier d'import pour DeclarVins](export_edi_complet.csv) .

Ce fichier reprend l'ensemble des spécificités décrites ci-dessus.

## Suivi du projet chez les éditeurs de registres de cave 

| Nom de l'Éditeur | Prise de contact | Génération du fichier de transfer | Recette des échanges en préproduction | Transmission opérationnelle en production | Versions compatibles |
|------------------|------------------|-----------------------------------|---------------------------------------|------------------------------------------------------|----------------------|
| CR2i             | Oui |  |  |  |  |
| ISAGRI           | Oui |  |  |  |  |
| ICS-SUD          | Oui |  |  |  |  |
| I3S              | Oui |  |  |  |  |
