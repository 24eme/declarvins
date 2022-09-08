# Spécifications techniques de l'implémentation du service EDI sur le portail DeclarVins à destination des interprofessions

## Architecture technique de sécurité

### Authentification des utilisateurs

L'interface EDI n'est accessible qu'après authentification. L'authentification nécessite que l'utilisateur possède un compte sur la plateforme de télédéclaration DeclarVins. Une fois ce compte créé, l'utilisateur pourra s'identifier sur la plateforme EDI en fournissant son login et mot de passe via le protocole d'authentification HTTP (HTTP Authentication Basic [1]).

Les informations relatives aux identifiants/mots de passe, aux cookies ou aux authentifications HTTP seront transférées en HTTPS [2] comme tout le reste des informations.

### Protocole technique utilisé

L'EDI mis à disposition est accessible à travers le protocole HTTPS. Pour l'envoi d'information, la méthode POST x-www-form-urlencoded [3] doit être implémentée.

### Échange de données

Les données échangées en mode lecture ou écriture se font sous le format CSV [4]. La plateforme supporte indifféremment les séparateurs virgules (« , ») ou point-virgules (« ; »). En revanche, il est nécessaire qu'un seul type de séparateur soit utilisé  au sein d'un même document.

La plateforme de télédéclération est insensible à la casse et aux caractères accentués. Les chaines de caractères « Côte » ou « cote » seront donc traitées de manière identique.
Il faut noter toute fois, qu'en cas d'utilisation de caractères accentués, ces caractères devront être encodés en UTF-8 [5]. 

Débuter une ligne par le caractère « # » permet de définir des commentaires. Elles ne sont donc pas prises en compte par la plateforme.

Les nombres décimaux peuvent avoir pour séparateur décimal une virgule « , » ou un point « . ». Dans le cas ou la virgule « , » est choisi, bien faire attention qu'il n'y ait pas de confusion avec le séparateur du CSV.

### Sécurité des transferts

Toutes les connexions réalisées sur l'interface de saisie des DRM se feront via le protocole HTTPS [2].

### Domaine dédié à l'EDI

Le nom de domaine de pré-production est : https://edi-preprodv2.declarvins.net   

Le nom de domaine de production est : https://edi.declarvins.net

### Envoi des informations par EDI

Voici les détails téchnique pour accéder au webservice d'envoi EDI :

 - Protocole : HTTPS
 - Authentification : HTTP Authentication Basic
 - Encodage des caractères : UTF-8
 - Format des données à fournir en entrée : CSV
 - Format des données fournies en sortie : Aucun ou CSV
 - Type de requete : POST x-www-form-urlencoded
 - URL : *mis à disposition sur le portail DeclarVins*
 
## Interface EDI DRM

L'url de récupération des DRM pour une interprofession est : 

/edi.php/edi/drm/\<\<interpro\>\>/\<\<date\>\>

 * \<\<interpro\>\> : correspond à l'identifiant de l'interprofession
 * \<\<date\>\> : correspondant à la date au format ISO 8601 [6] à partir de laquelle les DRM ont été saisies (le format horaire 00h00m00 est aussi accepté)
 
La nomenclature du fichier CSV retourné est : 

DRM_\<\<date demandée\>\>_\<\<date de saisie de la dernière DRM retournée\>\>.csv 

Les dates de la nomenclature sont aux formats : 

aaaa-mm-jjT00h00m00 (en effet le spérateur ":" du format horaire ISO 8601 [6] n'est pas un caractère autorisé en nom de fichier). 

### Spécification complète du format d'export des DRM
1. Type d'enregistrement : « DETAIL » pour un mouvement de DRM, « CONTRAT » pour une retiraison vrac
2. Identifiant DeclarVins
3. Raison sociale de l'établissement
4. Année DRM courante
5. Mois DRM courante
6. Version rectificative ou modificative DRM courante
7. Année DRM précédente
8. Mois DRM précédente
9. Version rectificative ou modificative DRM précédente
10. Le libellé de la certification
11. Le code de la certification
12. Le libellé du genre
13. Le code du genre
14. Le libellé de l'appellation
15. Le code de l'appellation
16. Le libellé du lieu
17. Le code du lieu
18. Le libellé de la couleur
19. Le code de la couleur
20. Le libellé du cepage
21. Le code du cepage
22. Millésime
23. (historique) champs vide
24. Labels : Libellés des labels (Biodynamie, Bio, Bio en conversion, Agriculture raisonnée), cumulables à l'aide du séparateur « | » (pipe)
25. Codes DeclarVins Labels : Codes des labels (biod, biol, bioc, rais), cumulables à l'aide du séparateur « | » (pipe)
26. Libellé complet du produit
27. Stock théorique début de mois
28. Dont Vin bloqué ; 
29. Dont Vin warranté ; 
30. Dont Vin en instance ; 
31. Dont Vin commercialisable ; 
32. Volume total des entrées
33. Volume entrée Achats / réintégration
34. Volume entrée Récolte / revendication
35. Volume entrée Mvt. interne : Replis / Changt. de dénomination
36. Volume entrée Mvt. interne : Déclassement / Lies
37. Volume entrée Mvt. temporaire : Retour transfert de chai
38. Volume entrée Replacement en suspension CRD
39. Volume total des sorties
40. Volume sortie Vrac DAA/DAE
41. Volume sortie Conditionné export
42. Volume sortie DSA / tickets / factures
43. Volume sortie CRD France
44. Volume sortie Conso Fam. / Analyses / Dégustation
45. Volume sortie Autres sorties
46. Volume sortie Mvt. interne : Non rev. / Déclassement
47. Volume sortie Mvt. interne : Changement / Repli
48. Volume sortie Mvt. temporaire : Transfert de chai
49. Volume sortie Destruction / Distillation
50. Volume sortie Lies
51. Stock théorique fin de mois
52. Dont Vin bloqué
53. Dont Vin warranté
54. Dont Vin en instance
55. Dont Vin commercialisable
56. Date validation
57. Date Signature (historique : doublon avec 56)
58. Mode de saisie : PAPIER si saisie par l'interprofession, DTI si saisie par le déclarant, EDI si échange informatique avec logiciel déclarant
59. CVO Code
60. CVO Taux
61. CVO Volume (40.Volume sortie Vrac DAA/DAE + 41.Volume sortie Conditionné export + 42.Volume sortie DSA / tickets / factures + 43.Volume sortie CRD France + 84.Volume sortie CRD Collectives acquittées + 99.Volume sortie Vrac Export)
62. CVO Montant
63. Campagne DRM
64. ID de cette DRM : concaténation de DRM-IDDeclarant-AnnéeDRM-MoisDRM-RevisionDRM
65. Identifiant IVSE
66. Numéro du contrat : Pour enregistrement « CONTRAT »
67. Volume du contrat : Pour enregistrement « CONTRAT »
68. Commentaires DRM saisis en back office par l'opérateur  
69. DRM référente : 0 ou 1
70. Contrat Vrac manquant : 0 ou 1
71. Infos IGP manquantes : 0 ou 1
72. Observation pour le produit saisies par le télédéclarant 
73. Volume entrée vci
74. Famille du déclarant (Producteur, Négociant, Courtier)
75. Sous famille du déclarant (Cave particulière, Cave coopérative, Vendeur de raisin, Régional, Extérieur, Etranger, Union, Vinificateur)
76. Identifiant INAO du produit
77. Libellé fiscal du produit (en l'absence de code INAO)
78. TAV (taux d'alcool volumique)
79. Volume entrée Mvt. interne : Augmentation de volume
80. Volume entrée Mvt. temporaire : Retour embouteillage
81. Volume entrée Mvt. temporaire : Retour de travail à façon
82. Volume entrée Mvt. temporaire : Retour de distillation à façon
83. Volume entrée Excédent suite à inventaire ou contrôle douanes
84. Volume sortie CRD Collectives acquittées
85. Volume sortie Mvt. interne : Mutage
86. Volume sortie Mvt. interne : Revendication de VCI
87. Volume sortie Mvt. interne : Autres
88. Volume sortie Mvt. temporaire : Embouteillage
89. Volume sortie Mvt. temporaire : Travail à façon
90. Volume sortie Mvt. temporaire : Distillation à faço
91. Stock acquitté théorique début de mois
92. Volume acquitté entrée Achats
93. Volume acquitté entrée Autres
94. Volume acquitté sortie Ventes de produits
95. Volume acquitté sortie Replacement en suspension 
96. Volume acquitté sortie Autres
97. Stock acquitté théorique fin de mois
98. Montant de l'avoir (Volume entrée Replacement en suspension CRD x CVO Taux)
99. Volume sortie Vrac Export

### Format d'export des DRM orienté CIEL

L'url de récupération des DRM pour une interprofession est :

/edi.php/edi/v2/drm/\<\<interpro\>\>/\<\<date\>\>

 * \<\<interpro\>\> : correspond à l'identifiant de l'interprofession
 * \<\<date\>\> : correspondant à la date au format ISO 8601 [6] à partir de laquelle les DRM ont été saisies (le format horaire 00h00m00 est aussi accepté)

La spécification complète du format d'export est détaillée ici : [Spécification complète du format d'export des DRM](https://github.com/24eme/declarvins/tree/master/doc/logiciels-tiers/)

## Interface EDI Contrat d'achat

L'url de récupération des contrats d'achat pour une interprofession est : 

/edi.php/edi/vrac/\<\<interpro\>\>/\<\<date\>\>

 * \<\<interpro\>\> : correspond à l'identifiant de l'interprofession
 * \<\<date\>\> : correspondant à la date au format ISO 8601 [6] à partir de laquelle les Contrats ont été saisies (le format horaire 00h00m00 est aussi accepté)
 
Cet export fournira la liste complète des contrats de vente visés dont les produits concernent l'interprofession désignée.

La nomenclature du fichier CSV retourné est : 

VRAC_\<\<date demandée\>\>_\<\<date de saisie du dernier contrat retourné\>\>.csv 

### Spécification complète du format d'export des contrats de vente

1. Numéro de VISA du contrat
2. Date de statistique : date (format ISO 8601 (AAAA-MM-JJTHH:MM:SS)) de validation/signature du contrat (signé par l'ensemble des acteurs au contrat) ou date renseigné par l'opérateur qui saisi le contrat
3. Date de signature :  date (format ISO 8601 (AAAA-MM-JJTHH:MM:SS)) à laquelle le contrat a été validé / signé par l'ensemble des acteurs au contrat
4. Identifiant DeclarVins Acheteur
5. CVI de l'Acheteur
6. SIRET de l'Acheteur
7. Nom de l'Acheteur
8. Identifiant DeclarVins du Vendeur
9. CVI du Vendeur
10. SIRET du Vendeur
11. Nom du Vendeur
12. Identifiant DeclarVins du Courtier
13. SIRET du Courtier
14. Nom du Courtier
15. Type de produit : vrac / raisin / mout
16. Le libellé de la certification
17. Le code de la certification
18. Le libellé du genre
19. Le code du genre
20. Le libellé de l'appellation
21. Le code de l'appellation
22. Le libellé du lieu
23. Le code du lieu
24. Le libellé de la couleur
25. Le code de la couleur
26. Le libellé du cepage
27. Le code du cepage
28. Millésime 
29. Millésime (historique : doublon avec 28)
30. Labels : Libellés des labels (Conventionnel, Bio, Bio en conversion, HVE 3, Autre ou Libellé précisé à la saisie), cumulables à l'aide du séparateur « | » (pipe)
31. Codes DeclarVins Labels : Codes des labels (conv, biol, bioc, hve, autre), cumulables à l'aide du séparateur « | » (pipe)
32. Mentions : Libellés des mentions (Domaine, autre terme règlementé, Primeur, Marque, Autre ou Libellé précisé à la saisie), cumulables à l'aide du séparateur « | » (pipe)
33. Codes DeclarVins Mentions : Codes des labels (chdo, prim, marque, autre), cumulables à l'aide du séparateur « | » (pipe)
34. Condition Particulière : aucune (Aucune) / union (Apport contractuel à une union) / interne (Contrat interne entre deux entreprises liées)
35. Première Mise en Marché : 0 ou 1 (1 si le vendeur est un producteur)
36. Annexes: 0 ou 1
37. Volume ou quantité total du contrat : en HL pour le vrac et mout, en KG pour le raisin
38. Prix Unitaire : Prix unitaire net HT hors cotisation en HL pour le vrac et mout, en KG pour le raisin
39. Type de Prix : definitif / objectif / acompte
40. Mode de détermination du prix : Texte libre détaillant le mode de détermination du prix dans le cas d'un prix d'objectif ou d'acompte
41. Expédition Export : 0 ou 1
42. Paiement : echeancier_paiement (Echéancier dérogatoire selon accords interprofessionnels) / cadre_reglementaire (Cadre Réglementaire Général)
43. Référence contrat pluriannuel : Référence du contrat dans le cas d'un contrat pluriannuel, null (vide) le cas échéant (contrat ponctuel)
44. Le Vin sera : livre / retire 
45. Date de début de Retiraison : date de début de retiraison prévue au contrat (AAAA-MM-JJ)
46. Date Limite de Retiraison : date limite de retiraison prévue au contrat (AAAA-MM-JJ)
47. Dates échéance : dates de paiement dans le cas d'un échéancier (AAAA-MM-JJ), cumulables à l'aide du séparateur « | » (pipe)
48. (historique) champs vide
49. Montant échéance : Montant en euros correspondant à chaque échéance, cumulables à l'aide du séparateur « | » (pipe)
50. Numéro de Lot
51. Numéros des Cuves : cumulables à l'aide du séparateur « | » (pipe)
52. Volume des Cuves : cumulables à l'aide du séparateur « | » (pipe)
53. Dates de retiraison des Cuves : cumulables à l'aide du séparateur « | » (pipe)
54. Assemblage dans le lot : 0 ou 1
55. Millésimes dans le lot : cumulables à l'aide du séparateur « | » (pipe)
56. Pourcentage des Millésimes dans le lot : cumulables à l'aide du séparateur « | » (pipe)
57. Degré du lot
58. Allergènes : 0 ou 1
59. Statut du contrat : NONSOLDE / SOLDE / ANNULE
60. Commentaires : texte saisi par l'operateur
61. Version du contrat : null (vide) si contrat non rectifié/modifié. Sinon indique M (contrat modificatif) ou R (contrat rectificatif) + numéro de version
62. Contrat référent : 0 ou 1
63. Mode de saisie : PAPIER si saisie par l'interprofession, DTI si saisie par le déclarant, EDI si échange informatique avec logiciel déclarant
64. Date de saisie  : date (format ISO 8601 (AAAA-MM-JJTHH:MM:SS)) à laquelle le contrat a été soumis
65. Date de validation/VISA  : date (format ISO 8601 (AAAA-MM-JJTHH:MM:SS)) de validation/signature du contrat (signé par l'ensemble des acteurs au contrat) ou date renseigné par l'opérateur qui saisi le contrat
66. Observations : texte saisi par le télédéclarant
67. Bailleur à fruit à métayer : 0 ou 1
68. Date de l'envoi à l'OCo : date (AAAA-MM-JJ) de mise à disposition des informations de transaction
69. Date de chargement par l'OCo : date (AAAA-MM-JJ) de mise à disposition des informations de transaction (historique : doublon avec 68)
70. Date de traitement par l'OCo : date (AAAA-MM-JJ) de traitement du contrat par l'organisme
71. Adresse de stockage SIRET
72. Adresse de stockage Nom commercial
73. Adresse de stockage Adresse
74. Adresse de stockage Code Postal
75. Adresse de stockage Commune
76. Adresse de stockage Pays
77. Adresse de stockage Présence : 0 ou 1
78. Numéro d'Accises de l'Acheteur
78. Numéro d'Accises du Vendeur
79. Date de détermination du prix : date (AAAA-MM-JJ) de détermination du prix dans le cas d'un prix d'objectif ou d'acompte
80. Campagne du contrat
81. Prix total du contrat
82. Mois de la date de statistique
83. Année de la date de statistique
84. Type de retiraison : vrac (Retiraison/Livraison en Vrac) / tire_bouche (Retiraison/Livraison en Tiré Bouché) / lattes (Retiraison/Livraison sur Lattes)
85. Delai de paiement : 60_jours / 45_jours / autre
86. Prix Unitaire HL : Prix unitaire net HT hors cotisation en HL estimé pour le raisin (= prix unitaire pour le vrac et mout)
87. Volume total du contrat en HL : Volume total du contrat en HL pour le raisin (= volume total du contrat pour le vrac et mout)
88. Volume enlevé du contrat en HL : Volume enlevé du contrat en HL


   [1]: https://fr.wikipedia.org/wiki/Authentification_HTTP
   [2]: https://tools.ietf.org/html/rfc2818
   [3]: http://www.w3.org/TR/html401/interact/forms.html#h-17.13.4.1
   [4]: https://fr.wikipedia.org/wiki/Comma-separated_values
   [5]: https://fr.wikipedia.org/wiki/UTF-8
   [6]: https://fr.wikipedia.org/wiki/ISO_8601
