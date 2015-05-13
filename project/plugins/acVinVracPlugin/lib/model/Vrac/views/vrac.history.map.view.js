function(doc) {
  	if (doc.type != "Vrac") {
 		return;
    }
	var vrac_version = (doc.version)? doc.version : null;
	var vrac_referente = (doc.referente)? doc.referente : null;
	var date_contrat = (doc.valide.date_validation)? doc.valide.date_validation : doc.valide.date_saisie;
	var statut = (doc.valide.statut)? doc.valide.statut : 0;
	emit([statut, doc.interpro, doc.valide.date_saisie, doc._id], [doc.valide.statut, doc._id, doc.acheteur_identifiant, doc.acheteur.nom, doc.acheteur.raison_sociale, doc.vendeur_identifiant, doc.vendeur.nom, doc.vendeur.raison_sociale, doc.mandataire_identifiant, doc.mandataire.nom, doc.mandataire.raison_sociale, doc.type_transaction, doc.produit, doc.produit_libelle, doc.volume_propose, doc.volume_enleve, doc.prix_total, doc.prix_unitaire, doc.part_cvo, doc.millesime, doc.labels, doc.mentions, doc.mode_de_saisie, doc.acheteur_identifiant, doc.mandataire_identifiant, doc.vendeur_identifiant, doc.valide.date_validation_acheteur, doc.valide.date_validation_mandataire, doc.valide.date_validation_vendeur, doc.valide.date_saisie, doc.date_relance, doc.vous_etes, doc.numero_contrat, vrac_version, vrac_referente]);
}