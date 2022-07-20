function(doc) {
     if ((doc.type != "Vrac")) {

         return;
     }
     if ((doc.referente != 1)) {

         return;
     }

	var getCampagne = function(date) {
			if (!date) {
				return null;
			}
			var d = date.split('-');
			var annee = parseInt(d[0]);
			var mois = d[1];
			if (mois < 8) {
				annee--;
			}
			return annee+""+(annee+1);
		}


     var original = "OUI";

	 var ctype = "SPOT";
	 if (doc.contrat_pluriannuel == 1) {
       ctype = "PLURIANNUEL";
	 }

     var export = "NON";
	 if (doc.export == 1) {
       export = "OUI";
	 }

     var premiere_mise_en_marche = "NON";
	 if (doc.premiere_mise_en_marche == 1) {
       premiere_mise_en_marche = "OUI";
	 }

     var prix_variable = "NON";
     if (doc.type_prix != 'definitif') {
         prix_variable = "OUI";
     }

     var interne = "NON";
     if (doc.cas_particulier == 'interne') {
         interne = "OUI";
     }

     var archive = doc.numero_contrat;

     teledeclare = 1;
     if (doc.mode_de_saisie == 'PAPIER') {
 	   teledeclare = 0;
     }

     labels = (doc.labels_libelle)? ((doc.labels_libelle).replace(", ", "|")).replace(",", "|") : null;
     mentions = (doc.mentions_libelle)? ((doc.mentions_libelle).replace(", ", "|")).replace(",", "|") : null;


     clabels = (doc.labels_arr)? (doc.labels_arr).join('|') : null;
     cmentions = (doc.mentions)? (doc.mentions).join('|') : null;

	var campagne = "";
	if (doc.valide.date_validation) { campagne = getCampagne(doc.valide.date_validation); }
	if (!campagne && doc.valide.date_saisie) { campagne = getCampagne(doc.valide.date_saisie); }

     emit([teledeclare, doc.valide.date_saisie, doc._id, doc.interpro], [campagne, doc.valide.statut, doc._id, doc.numero_contrat, archive, doc.acheteur_identifiant, doc.acheteur.raison_sociale, doc.vendeur_identifiant, doc.vendeur.raison_sociale, doc.mandataire_identifiant,doc.mandataire.raison_sociale, null, null, doc.type_transaction, doc.produit, doc.produit_libelle, doc.volume_propose, doc.volume_enleve, doc.prix_unitaire, doc.prix_unitaire, prix_variable, interne, original, ctype, doc.date_signature, doc.date_stats, doc.valide.date_saisie, doc.millesime, null, doc.domaine, doc.part_variable, doc.cvo_repartition, doc.cvo_nature, null, null, labels, clabels, mentions, cmentions, export, premiere_mise_en_marche, doc.type_prix, doc.cas_particulier]);
 }
