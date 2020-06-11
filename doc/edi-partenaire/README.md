# Spécifications techniques de l'implémentation du service EDI sur le portail DeclarVins à destination des interprofessions partenaires

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

L'url de récupération des DRM pour une interprofession partenaire est : 

/edi.php/edi/v2/drm/\<\<interpro\>\>/\<\<date\>\>

 * \<\<interpro\>\> : correspond à l'identifiant de l'interprofession partenaire
 * \<\<date\>\> : correspondant à la date au format ISO 8601 [6] à partir de laquelle les DRM ont été saisies (le format horaire 00h00m00 est aussi accepté)
 
La nomenclature du fichier CSV retourné est : 

DRM_\<\<date demandée\>\>_\<\<date de saisie de la dernière DRM retournée\>\>.csv 

Les dates de la nomenclature sont aux formats : 

aaaa-mm-jjT00h00m00 (en effet le spérateur ":" du format horaire ISO 8601 [6] n'est pas un caractère autorisé en nom de fichier). 

Cet export fournira les mouvements, des produits relatifs à l'interprofession partenaire désignée ainsi que les produits sans indication géographique, des DRM.

La spécification complète du format d'import et d'export des DRM est détaillée ici : [Spécification DRM DeclarVins](https://github.com/24eme/declarvins/tree/master/doc/logiciels-tiers/). 

## Interface EDI Contrat d'achat

L'url de récupération des contrats d'achat pour une interprofession partenaire est : 

/edi.php/edi/v2/contrats-achat/\<\<interpro\>\>

 * \<\<interpro\>\> : correspond à l'identifiant de l'interprofession partenaire
 
Cet export fournira la liste complète des contrats d'achat visés et non soldés dont les produits concernent l'interprofession partenaire désignée.

La spécification complète du format d'export des contrats d'achat est détaillée ici : [Spécification Contrats d'achat DeclarVins](https://github.com/24eme/declarvins/tree/master/doc/logiciels-tiers/CONTRATS.md). 

## Interface EDI Transaction

L'url de récupération des transactions pour un organisme d'inspection / de controle partenaire est : 

/edi.php/edi/v2/transaction/\<\<oioc\>\>/\<\<date\>\>

 * \<\<oioc\>\> : correspond à l'identifiant de l'organisme partenaire
 * \<\<date\>\> : correspondant à la date au format ISO 8601 [6] à partir de laquelle les Contrats ont été saisies (le format horaire 00h00m00 est aussi accepté)
 
Cet export fournira la liste complète des transactions visés dont les produits concernent l'organisme partenaire désigné.

La nomenclature du fichier CSV retourné est : 

TRANSACTION_\<\<date demandée\>\>_\<\<date de saisie du dernier contrat retourné\>\>.csv 

La spécification complète du format d'export des transactions est détaillée ici : [Spécification transactions DeclarVins](https://github.com/24eme/declarvins/tree/master/doc/logiciels-tiers/TRANSACTIONS.md). 


   [1]: https://fr.wikipedia.org/wiki/Authentification_HTTP
   [2]: https://tools.ietf.org/html/rfc2818
   [3]: http://www.w3.org/TR/html401/interact/forms.html#h-17.13.4.1
   [4]: https://fr.wikipedia.org/wiki/Comma-separated_values
   [5]: https://fr.wikipedia.org/wiki/UTF-8
   [6]: https://fr.wikipedia.org/wiki/ISO_8601
