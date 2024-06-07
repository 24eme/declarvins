function(doc) {
  	if (doc.type != "Vrac") {
 		return;
    }
	var vrac_version = (doc.version)? doc.version : null;
	var vrac_referente = (doc.referente)? doc.referente : null;
	var date_contrat = (doc.valide.date_validation)? doc.valide.date_validation : doc.valide.date_saisie;
	var statut = (doc.valide.statut)? doc.valide.statut : "ATTENTE_VALIDATION";
	var vrac_oioc_date_reception = (doc.oioc && doc.oioc.date_reception)? doc.oioc.date_reception : null;
	var vrac_oioc_date_traitement = (doc.oioc && doc.oioc.date_traitement)? doc.oioc.date_traitement : null;
	var vrac_oioc_identifiant = (doc.oioc && doc.oioc.identifiant)? doc.oioc.identifiant : null;
	var vrac_oioc_statut = (doc.oioc && doc.oioc.statut)? doc.oioc.statut : null;
        var vrac_pluriannuel = (doc.contrat_pluriannuel && doc.contrat_pluriannuel == 1)? 1 : 0;
        var vrac_reference_pluriannuel = (doc.reference_contrat_pluriannuel)? doc.reference_contrat_pluriannuel : null;
        var vrac_quantite_libelle = '';
        if (doc.volume_propose) vrac_quantite_libelle = doc.volume_propose+' hl';
        else if (doc.surface) vrac_quantite_libelle = doc.surface+' ha';
        else if (doc.pourcentage_recolte) vrac_quantite_libelle = doc.surface+' %R';
        else vrac_quantite_libelle = '';
        var statut_acheteur = doc.valide.date_validation_acheteur;
        var statut_vendeur = doc.valide.date_validation_vendeur;
        var statut_mandataire = doc.valide.date_validation_mandataire;
        if (doc.annulation) {
        	statut_acheteur = doc.annulation.date_annulation_acheteur;
        	statut_vendeur = doc.annulation.date_annulation_vendeur;
        	statut_mandataire = doc.annulation.date_annulation_mandataire;
        }
	var vue = [doc.valide.statut, doc._id, doc.acheteur_identifiant, doc.acheteur.nom, doc.acheteur.raison_sociale, doc.vendeur_identifiant, doc.vendeur.nom, doc.vendeur.raison_sociale, doc.mandataire_identifiant, doc.mandataire.nom, doc.mandataire.raison_sociale, doc.type_transaction, doc.produit, doc.produit_libelle+' '+doc.labels_libelle, doc.volume_propose, doc.volume_enleve, doc.prix_total, doc.prix_unitaire, doc.part_cvo, doc.millesime, doc.labels, doc.mentions, doc.mode_de_saisie, doc.acheteur_identifiant, doc.mandataire_identifiant, doc.vendeur_identifiant, statut_acheteur, statut_mandataire, statut_vendeur, doc.valide.date_saisie, doc.date_relance, doc.vous_etes, doc.numero_contrat, vrac_version, vrac_referente, vrac_oioc_identifiant, vrac_oioc_statut, vrac_oioc_date_reception, vrac_oioc_date_traitement, doc.poids, vrac_reference_pluriannuel, vrac_quantite_libelle];
	emit([vrac_pluriannuel, statut, doc.interpro, doc.valide.date_saisie, doc._id], vue);
    	if (doc.mandataire_exist) {
    		emit([doc.mandataire_identifiant, vrac_pluriannuel, statut, doc.interpro, doc.valide.date_saisie, doc._id],  vue);
    	}
    	emit([doc.acheteur_identifiant, vrac_pluriannuel, statut, doc.interpro, doc.valide.date_saisie, doc._id], vue);
    	emit([doc.vendeur_identifiant, vrac_pluriannuel, statut, doc.interpro, doc.valide.date_saisie, doc._id], vue);
}
