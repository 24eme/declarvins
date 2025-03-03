# Spécifications techniques de l'implémentation du format de transaction attendues sur le portail DeclarVins

## Organisation générale 

Le fichier CSV permet de déclarer les différentes informations liées à la transaction.

L'idée du fichier CSV est de permettre d'autres exploitations que celles liées à la télédéclaration des transactions. Certaines informations peuvent être éclatées en plusieurs champs afin par exemple de permettre des utilisations statistiques (c'est le cas notamment pour la description des produits).

Chaque ligne du fichier correspond à une transaction.

## Spécification complète du format d'export des transactions

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
12. *Champ vide pour les transactions*
13. *Champ vide pour les transactions*
14. *Champ vide pour les transactions*
15. Type de produit : vrac *(uniquement pour les transactions)*
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
28. *Champ vide pour les transactions* 
29. *Champ vide pour les transactions*
30. *Champ vide pour les transactions*
31. *Champ vide pour les transactions*
32. *Champ vide pour les transactions*
33. *Champ vide pour les transactions*
34. *Champ vide pour les transactions*
35. Première Mise en Marché : 0 (non) ou 1 (oui)
36. *Champ vide pour les transactions*
37. Volume total du contrat en HL
38. *Champ vide pour les transactions*
39. *Champ vide pour les transactions*
40. *Champ vide pour les transactions*
41. Expédition Export : 0 (non) ou 1 (oui)
42. *Champ vide pour les transactions*
43. *Champ vide pour les transactions*
44. *Champ vide pour les transactions*
45. Date de début de retiraison prévue au contrat (AAAA-MM-JJ)
46. *Champ vide pour les transactions*
47. *Champ vide pour les transactions*
48. *Champ vide pour les transactions*
49. *Champ vide pour les transactions*
50. Numéro de Lot
51. Numéros des Cuves : cumulables à l'aide du séparateur « | » (pipe)
52. Volume des Cuves : cumulables à l'aide du séparateur « | » (pipe)
53. Dates de retiraison des Cuves : cumulables à l'aide du séparateur « | » (pipe)
54. Assemblage dans le lot : 0 (non) ou 1 (oui)
55. Millésimes dans le lot : cumulables à l'aide du séparateur « | » (pipe)
56. Pourcentage des Millésimes dans le lot : cumulables à l'aide du séparateur « | » (pipe)
57. *Champ vide pour les transactions*
58. *Champ vide pour les transactions*
59. Statut du contrat : NONSOLDE / SOLDE / ANNULE
60. *Champ vide pour les transactions*
61. Version du contrat : null (vide) si contrat non rectifié/modifié. Sinon indique M (contrat modificatif) ou R (contrat rectificatif) + numéro de version
62. Contrat référent : 0 (non) ou 1 (oui)
63. Mode de saisie : PAPIER si saisie par l'interprofession, DTI si saisie par le déclarant, EDI si échange informatique avec logiciel déclarant
64. Date de saisie  : date (format ISO 8601 (AAAA-MM-JJTHH:MM:SS)) à laquelle le contrat a été soumis
65. Date de validation/VISA  : date (format ISO 8601 (AAAA-MM-JJTHH:MM:SS)) de validation/signature du contrat (signé par l'ensemble des acteurs au contrat) ou date renseigné par l'opérateur qui saisi le contrat
66. *Champ vide pour les transactions*
67. Bailleur à fruit à métayer : 0 (non) ou 1 (oui)
68. Date de l'envoi à l'OCo : date (AAAA-MM-JJ) de mise à disposition des informations de transaction
69. Date de chargement par l'OCo : date (AAAA-MM-JJ) de mise à disposition des informations de transaction (historique : doublon avec 68)
70. Date de traitement par l'OCo : date (AAAA-MM-JJ) de traitement du contrat par l'organisme
71. Adresse de stockage SIRET
72. Adresse de stockage Nom commercial
73. Adresse de stockage Adresse
74. Adresse de stockage Code Postal
75. Adresse de stockage Commune
76. Adresse de stockage Pays
77. Adresse de stockage Présence : 0 (oui) ou 1 (non)
78. Numéro d'Accises de l'Acheteur
78. Numéro d'Accises du Vendeur
79. Type de retiraison : vrac (Retiraison/Livraison en Vrac) / tire_bouche (Retiraison/Livraison en Tiré Bouché) / lattes (Retiraison/Livraison sur Lattes)
80. Commune de l'acheteur
81. Pays de l'acheteur
