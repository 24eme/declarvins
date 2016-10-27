#Spécifications techniques de l'implémentation du format de contrat d'achat attendues sur le portail DeclarVins

##Organisation générale 

Le fichier CSV permet de déclarer les différentes informations liées au contrat d'achat.

L'idée du fichier CSV est de permettre d'autres exploitations que celles liées à la télédéclaration des contrats d'achat. Certaines informations peuvent être éclatées en plusieurs champs afin par exemple de permettre des utilisations statistiques (c'est le cas notamment pour la description des produits).

Chaque ligne du fichier correspond à un contrat d'achat. Cette structure s'organise autour de cinq sections de champs :
 - la partie commune (4 champs) qui fournit les informations liées au contrat d'achat
 - la partie identification du produit (9 champs) qui permet d'identifier le vin déclaré
 - les volumes de produit concerné (2 champs) qui permettent de connaître le volume initial et le volume retiré du produit

La partie identification du produit peut être utilisé soit de manière éclaté (qui permet de faire des exploitations statistiques sur les appellations, les couleurs, ...), soit de manière agrégé en indiquant le nom complet du produit.

###Description des lignes 

 **Pour la section commune :**
 
 1. Le numéro de VISA du contrat d'achat (champ obligatoire) 
 2. L'identifiant interpro de l'établissement vendeur (champ alpha-numérique) pouvant contenir entre parenthèses le numéro SIRET (14 chiffres) ou CVI (10 chiffres) de l'établissement
 3. Le numéro d'accise de l'établissement vendeur (champ alpha-numérique de 13 caractères au format FR0xxxxxxxxxx)
 4. Le libellé désignant l'établissement acheteur (champ libre obligatoire contenant la raison sociale, le nom commercial,...)
 
Pour identifier l'établissement vendeur, il est obligatoire de renseigner au moins une valeur entre l'identifiant interpro, le siret, le cvi et le numéro d'accise.

 **Pour l'identification du vin :**

 5. Le code certification du vin (champ obligatoire si la colonne 13 n'est pas renseigné)  
 6. Le code genre du vin (champ obligatoire si la colonne 13 n'est pas renseigné)  
 7. Le code appellation du vin (champ facultatif)
 8. Le code mention du vin (champ facultatif)  
 9. Le code lieu du vin (champ facultatif)
 10. Le code couleur du vin (champ obligatoire si la colonne 13 n'est pas renseigné)
 11. Le code cépage du vin (champ facultatif)
 12. Le libellé personnalisé du vin (champ facultatif sauf si les colonnes 5 à 12 ne sont pas renseignées) pouvant contenir entre parenthèses le code INAO ou le libellé fiscal du produit
 13. Le millésime du vin (champ facultatif)
 
Pour identifier un produit, il est obligatoire de renseigner les codes du produit de manière éclatée (colonnes 5 à 12) et/ou le libellé du produit (libellé et/ou entre parenthèses le code INAO / le libellé fiscal).

 **Pour les volumes :**
 
 14. Le volume initial : (champ obligatoire)
 15. Le volume retiré : (champ facultatif)

###Exemple de CSV complet

[Vous trouverez ici un exemple complet](export_edi_complet_contrats.csv "csv_complet")