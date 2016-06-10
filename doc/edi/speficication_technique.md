#Spécifications techniques de l'EDI DRM de DeclarVins

##Architecture technique de sécurité

###Authentification des utilisateurs

Tous les utilisateurs déclarant ayant accès à la plateforme devront être authentifiés. Avant de pouvoir consulter n'importe quelle page, les utilisateurs doivent donc s'identifier sur le service d'authentification unique et centralisé CAS [1].

Cette authentification se réalisera sur la base d'un identifiant et d'un mot de passe connus des seuls utilisateurs.

Sur l'application, les utilisateurs seront reconnus via un cookie de session fourni par le framework Symfony pour l'interface DTI et via une authentification HTTP [2] pour l'EDI.

Les informations relatives aux identifiants/mots de passe, aux cookies ou aux authentifications HTTP seront transférées en HTTPS [3] comme tout le reste des informations.

###Authentification - EDI

L'interface EDI n'est accessible qu'après authentification. L'authentification nécessite que l'utilisateur possède un compte sur la plateforme de télédéclaration DeclarVins. Une fois ce compte créé, l'utilisateur pourra s'identifier sur la plateforme EDI en fournissant son login et mot de passe via le protocole d'authentification HTTP (HTTP Authentication Basic [2]).

###Protocole technique utilisé

L'EDI mis à disposition des vignerons est accessible à travers le protocole HTTPS. Pour l'envoi d'information, la méthode POST x-www-form-urlencoded [4] doit être implémentée.

###Échange de données

Les données échangées en mode lecture ou écriture se font sous le format CSV [5]. La plateforme supporte indifféremment les séparateurs virgules (« , ») ou point-virgules (« ; »). En revanche, il est nécessaire qu'un seul type de séparateur soit utilisé  au sein d'un même document.

La plateforme de télédéclération est insensible à la casse et aux caractères accentués. Les chaines de caractères « Côte » ou « cote » seront donc traitées de manière identique.
Il faut noter toutefois, qu'en cas d'utilisation de caractères accentués, ces caractères devront être encodés en UTF-8 [6]. 

Débuter une ligne par le caractère « # » permet de définir des commentaires. Elles ne sont donc pas prises en compte par la plateforme.

Les nombres décimaux peuvent avoir pour séparateur décimal une virgule « , » ou un point « . ». Dans le cas ou la virgule « , » est choisie, bien faire attention qu'il n'y ait pas de confusion avec le séparateur du CSV.

###Sécurité des transferts

Toutes les connexions réalisées sur l'interface de saisie des DRM se feront via le protocole HTTPS [3].

##Description de l'interface DRM

La création d'une DRM préremplie sur la plateforme de télédéclaration DeclarVins peut se faire par envoi automatique depuis un logiciel tiers : c'est l'interface EDI.

###Domaine dédié à l'EDI

Un nom de domaine est dédié aux tests et un autre à la production, les URL fournies dans ce document font abstraction du nom de domaine à utiliser.

Les noms de domaine de production et de production sont les suivants :
 - production : https://edi.declarvins.net/
 - preproduction : https://preprodv2.declarvins.net/edi.php/

###Envoi des informations par EDI

Voici les détails téchniques pour accéder au webservice d'envoi EDI d'une DRM :

 - Protocole : HTTPS
 - Authentification : HTTP Authentication Basic
 - Encodage des caractères : UTF-8
 - Format des données à fournir en entrée : CSV
 - Format des données fournies en sortie : Aucun
 - Type de requete : POST x-www-form-urlencoded
 - URL : /edi/v2/push/etablissement/drm/:id_etablissement:
   avec :
   - :id_etablissement: : l'identifiant DeclarVins de l'établissement
   - csv[file] : Argument post contenant les données CSV de la DRM à importer.

###Format du fichier CSV

Le fichier CSV permet de déclarer les différentes informations liées à la DRM.

Les premiers champs de chaque ligne sont des champs communs pour tout le fichier, ils décrivent :
 - le type de ligne concernée (CAVE, RETIRAISON, CRD, ANNEXE, comme décrit plus bas)
 - la date de la DRM courante (format AAAA-MM)
 - l'identifiant DeclarVins de l'établissement
 - le numéro d'accises du ressortisant

Le fichier CSV est constitué de quatre types de lignes :
 - CAVE : pour déclarer le stock et les mouvements de cave ;
 - RETIRAISON : pour déclarer la retiraison d'un contrat d'achat ;
 - CRD : pour déclarer le stock et les mouvements de CRD ;
 - ANNEXE : pour les informations demandées en annexe par les douanes (documents d'accompagnement, statistiques européennes, observations, ...)

L'idée du fichier CSV est de permettre d'autres exploitations que celles liées à la télédéclaration des DRM. Certaines informations peuvent être éclatées en plusieurs champs afin par exemple de permettre des utilisations statistiques (c'est le cas notamment pour la description des produits).

Les quatres types de lignes se basent sur une structure commune. Cette structure s'organise autour des 5 sections de champs :
 - la partie commune (4 champs) qui fournit les informations liées à la DRM et permet d'identifier le type de ligne
 - la partie identification du produit (10 champs) qui permet d'identifier le vin déclaré, le type de CRD ou d'annexe)
 - la partie identification du mouvement (2 champs) qui permet d'idenfifier le type de mouvement ou le stock concerné
 - la quantité du produit concerné (1 champs) qui permet de connaître le volume ou la quantité associée au mouvement concerné
 - la partie complément (4 champs) qui permet d'indiquer les informations complémentaires nécessaires à la déclaration du mouvement (informations relatives aux documents d'accompagnement, n° de contrat concerné, observations ...)

###Description des lignes CAVE

Les lignes de CAVE se constituent des champs suivants :

 **Pour la section commune :**
 
 1. CAVE : champs obligatoire à valeur fixe
 2. Période de la DRM : champs obligatoire au format AAAA-MM
 3. Identification de l'établissement : champs alpha-numérique obligatoire 
 4. Numéro d'accises : champs alpha-numérique de 13 caractères obligatoire

 **Pour l'identification du vin :**
 
 5. Type de droit du vin : champs obligatoire, deux valeurs acceptées « SUSPENDUS » ou « ACQUITTES »
 6. Code certification du vin : champs obligatoire
 7. Code genre du vin : champs obligatoire
 8. Code appellation du vin : champs facultatif
 9. Code mention du vin : champs facultatif
 10. Code lieu du vin : champs facultatif
 11. Code couleur du vin : champs obligatoire
 12. Code cépage du vin : champs facultatif
 13. Libellé du vin : champs facultatif
 14. Code label du vin : champs facultatif
 
Ces codes internes à DeclarVins seront fournis sur demande.
 
 **Pour le type de mouvement :**
 
 15. La catégorie de mouvement : champs facultatif, deux valeurs acceptées « entrees » ou « sorties »
 16. Le type de mouvement : champs obligatoire, valeurs acceptées :
 
####SUSPENDUS
 
 * total_debut_mois (Stock théorique début de mois)
 * tav (Taux d'alcool volumique)
 * premix (vaut 1 si le produit est un premix)
 
En entrée : 
 
 * achat (Achats / réintégration)
 * recolte (Récolte / revendication)
 * repli (Mvt. interne : Replis / Changt. de dénomination)
 * declassement (Mvt. interne : Déclassement / Lies)
 * manipulation (Mvt. interne : Augmentation de volume)
 * vci (Mvt. interne : Intégration issue de VCI)
 * mouvement (Mvt. temporaire : Retour transfert de chai)
 * embouteillage (Mvt. temporaire : Retour embouteillage)
 * travail (Mvt. temporaire : Retour de travail à façon)
 * distillation (Mvt. temporaire : Retour de distillation à façon)
 * crd (Replacement en suspension CRD)
 * excedent (Excédent suite à inventaire ou contrôle douanes)
 
En sortie :
 
 * vrac (Vrac DAA / DAE)
 * export (Conditionné export)
 * factures (DSA / Tickets / Factures)
 * crd (CRD France)
 * crd_acquittes (CRD Collectives acquittées)
 * consommation (Conso Fam. / Analyses / Dégustation)
 * pertes (Pertes exceptionnelles)
 * declassement (Mvt. interne : Non rev. / Déclassement)
 * repli (Mvt. interne : Changement / Repli)
 * mutage (Mvt. interne : Mutage)
 * vci (Mvt. interne : Revendication de VCI)
 * autres_interne (Mvt. interne : Autres)
 * mouvement (Mvt. temporaire : Transfert de chai)
 * distillation (Mvt. temporaire : Distillation à façon)
 * embouteillage (Mvt. temporaire : Embouteillage)
 * travail (Mvt. temporaire : Travail à façon)
 * autres (Destruction / Distillation)
 * lies (Lies)
 
####ACQUITTES
  
 * total_debut_mois (Stock théorique début de mois)
 * tav (Taux d'alcool volumique)
 * premix (vaut 1 si le produit est un premix)
 
En entrée : 
 
 * achat (Achats)
 * autres (Autres)
 
En sortie :
 
 * crd (Ventes de produits)
 * replacement (Replacement en suspension)
 * autres (Autres)
 
Les stocks théoriques fin de mois seront automatiquement calculés afin de conserver la cohérence de la saisie.
 
 **Pour le volume :**
 
 17. Le volume en hl : champs obligatoire au format nombre flottant
 
 **Pour les compléments :**
 
 18. la période complétant l'entrée replacement en suspension CRD : champs obligatoire en cas de volume d'entrée replacement en suspension CRD au format AAAA-MM

###Description les lignes RETIRAISON

 **Pour la section commune :**
 
 1. RETIRAISON : champs obligatoire à valeur fixe
 2. Période de la DRM : champs obligatoire au format AAAA-MM
 3. Identification de l'établissement : champs alpha-numérique obligatoire 
 4. Numéro d'accises : champs alpha-numérique de 13 caractères obligatoire
 
 **Pour la description du produit :**
 
 5. Type de droit du vin : champs obligatoire, valeur fixe « SUSPENDUS »
 6. Code certification du vin : champs obligatoire
 7. Code genre du vin : champs obligatoire
 8. Code appellation du vin : champs facultatif
 9. Code mention du vin : champs facultatif
 10. Code lieu du vin : champs facultatif
 11. Code couleur du vin : champs obligatoire
 12. Code cépage du vin : champs facultatif
 13. Libellé du vin : champs facultatif
 14. Code label du vin : champs facultatif
 
Ces codes internes à DeclarVins seront fournis sur demande.

 **Pour le type de mouvement :**
 
 15. Vide
 16. Vide

 **Pour le volume :**
 
 17. Le volume en hl : champs obligatoire au format nombre flottant
 
 **Pour les compléments :**
 
 18. Vide
 19. Numéro de VISA du contrat : champs obligatoire au format nombre entier
 20. Vide
 21. Vide

###Description des lignes CRD

 **Pour la section commune :**
 
 1. CRD : champs obligatoire à valeur fixe
 2. Période de la DRM : champs obligatoire au format AAAA-MM
 3. Identification de l'établissement : champs alpha-numérique obligatoire 
 4. Numéro d'accises : champs alpha-numérique de 13 caractères obligatoire
 
 **Pour l'identification de la CRD :**
 
 5. Type de droit de la CRD : champs obligatoire, deux valeurs acceptées « SUSPENDUS » ou « ACQUITTES »
 6. Type de la CDR : champs obligatoire, trois valeurs acceptées « PERSONNALISEES », « COLLECTIVES_DROITS_SUSPENDUS » ou « COLLECTIVES_DROITS_ACQUITTES »
 7. Catégorie fiscale de la CRD : champs obligatoire, trois valeurs acceptées « M » pour les vins mousseux, « T » pour les vins tranquilles ou « PI » pour les produits intermédiaires
 8. Centilitrage de la CRD : champs obligatoire, valeurs acceptées :
 
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
 
 9. Vide
 10. Vide
 11. Vide
 12. Vide
 13. Libellé de la CRD : champs facultatif
 14. Vide
 
 **Pour le type de mouvement :**
 
 15. La catégorie de mouvement : champs facultatif, deux valeurs acceptées « entrees » ou « sorties »
 16. Le type de mouvement : champs obligatoire, valeurs acceptées :
  
 * total_debut_mois (Stock théorique début de mois)
 
En entrée :
 
 * achats (Achats)
 * excedents (Excédents)
 * retours (Retours)
 
En sortie :
 
 * utilisees (Utilisées)
 * detruites (Détruites)
 * manquantes (Manquantes)
 
 Les stocks théoriques fin de mois seront automatiquement calculés afin de conserver la cohérence de la saisie.
 
 **Pour la quantité :**
 
 17. Quantité de CRD : champs obligatoire au format nombre entier

Il n'y a pas de champs complémentaires pour les CRD.


###Description des lignes ANNEXE

 **Pour la section commune :**
 
 1. ANNEXE : champs obligatoire à valeur fixe
 2. Période de la DRM : champs obligatoire au format AAAA-MM
 3. Identification de l'établissement : champs alpha-numérique obligatoire 
 4. Numéro d'accises : champs alpha-numérique de 13 caractères obligatoire
 
 **Pour la description du produit :**
 
 5. Type d'annexe : champs obligatoire, quatre valeurs acceptées « DOCUMENT » pour les documents d'accompagnements, « NON_APUREMENT » pour les relevés de non apurements, « STATISTIQUES » pour les statistiques européennes ou « OBSERVATIONS » pour les observations relatives aux mouvements CAVE d'un vin.
 
####Pour le type OBSERVATIONS

 6. Code certification du vin : champs obligatoire
 7. Code genre du vin : champs obligatoire
 8. Code appellation du vin : champs facultatif
 9. Code mention du vin : champs facultatif
 10. Code lieu du vin : champs facultatif
 11. Code couleur du vin : champs obligatoire
 12. Code cépage du vin : champs facultatif
 13. Libellé du vin : champs facultatif
 14. Code label du vin : champs facultatif
 
Ces codes internes à DeclarVins seront fournis sur demande.
 
####Pour les autres types DOCUMENT, NON_APUREMENT et STATISTIQUES
 
 6. Vide
 7. Vide
 8. Vide
 9. Vide
 10. Vide
 11. Vide
 12. Vide
 13. Vide
 14. Vide
 
 **Pour les catégories d'annexe :**
 
####Pour les types OBSERVATIONS et NON_APUREMENT
 
 15. Vide 
 16. Vide
 
####Pour les types DOCUMENT et STATISTIQUES
 
 15. Catégorie d'annexe : champs obligatoire, valeurs acceptées « empreinte » pour les numéros d'empreinte, « daa » pour les numéros DAA/DCA et « dsa » pour les numéros DSA/DSAC pour le type DOCUMENT et « statistiques » pour le type STATISTIQUES
 16. Type d'annexe : champs obligatoire, valeurs acceptées « debut » ou « fin » pour le type DOCUMENT et « jus », « mcr » et « vinaigre » pour le type STATISTIQUES
 
 **Pour la valeur :**
 
####Pour les types OBSERVATIONS et NON_APUREMENT
 
 17. Vide 
 
####Pour les types DOCUMENT et STATISTIQUES

 17. Valeur d'annexe : champs obligatoire au format nombre entier pour le type DOCUMENT et nombre flottant pour le type STATISTIQUES
 
 **Pour les compléments :**
 
####Pour les types DOCUMENT et STATISTIQUES
 
 18. Vide
 19. Vide
 20. Vide
 21. Vide
 
####Pour le type OBSERVATIONS
 
 18. Vide
 19. Vide
 20. Vide
 21. Observation : champs obligatoire au format alpha-numérique limitée à 250 caractères
 
####Pour le type NON_APUREMENT
 
 18. Date d'expédition : champs obligatoire au format AAAA-MM-DD
 19. Numéro d'accise du destinataire : champs obligatoire au format alpha-numérique de 13 caractères
 20. Numéro DAA/DAC/DAE : champs obligatoire au format nombre entier
 21. Vide

###Exemple de CSV complet

[Vous trouverez ici un exemple complet possédant plusieurs produits, crds et annexes différentes](https://github.com/24eme/declarvins/blob/edi/doc/edi/export_edi_complet.csv "csv_complet")

###Description du fichier en retour de transfert

Après chaque requête, l'EDI renvoie un fichier CSV contenant le rapport d'import de la DRM. Ce fichier se compose des champs et données suivants :

1. Type de retour : Chaîne de caractère valant « SUCCESS » en cas de chargement de la DRM réalisé avec succès ou « ERREUR » en cas de chargement d'un fichier erroné
2. Type d'erreur : Chaîne de caractère valant « ACCES » en cas d'erreur portant sur l'accès à l'EDI ou « CSV » en cas d'erreur portant sur les données du fichier importé matérialisant une DRM 
3. Numéro de ligne  : Numéro de la ligne du fichier importé comportant une erreur ou vide si il s'agit d'une erreur globale
4. Message : Message décrivant l'erreur
5. Valeur : Valeur de la donnée en erreur ou vide si il s'agit d'une erreur globale


   [1]: https://jasig.github.io/cas/4.0.x/index.html
   [2]: https://fr.wikipedia.org/wiki/Authentification_HTTP
   [3]: https://tools.ietf.org/html/rfc2818
   [4]: http://www.w3.org/TR/html401/interact/forms.html#h-17.13.4.1
   [5]: https://fr.wikipedia.org/wiki/Comma-separated_values
   [6]: https://fr.wikipedia.org/wiki/UTF-8
