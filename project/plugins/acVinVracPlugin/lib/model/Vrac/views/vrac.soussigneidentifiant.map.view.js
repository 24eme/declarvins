function(doc) {
    if (doc.type != "Vrac") {
            return;
        }

	var vrac_version = (doc.version)? doc.version : null;
	var vrac_referente = (doc.referente)? doc.referente : null;
	var vrac_oioc_date_reception = (doc.oioc && doc.oioc.date_reception)? doc.oioc.date_reception : null;
	var vrac_oioc_date_traitement = (doc.oioc && doc.oioc.date_traitement)? doc.oioc.date_traitement : null;
	var vrac_oioc_identifiant = (doc.oioc && doc.oioc.identifiant)? doc.oioc.identifiant : null;
	var vrac_oioc_statut = (doc.oioc && doc.oioc.statut)? doc.oioc.statut : null;
    var vue = [doc.valide.statut, doc._id, doc.acheteur_identifiant, doc.acheteur.nom, doc.acheteur.raison_sociale, doc.vendeur_identifiant, doc.vendeur.nom, doc.vendeur.raison_sociale, doc.mandataire_identifiant, doc.mandataire.nom, doc.mandataire.raison_sociale, doc.type_transaction, doc.produit, doc.produit_libelle, doc.volume_propose, doc.volume_enleve, doc.prix_total, doc.prix_unitaire, doc.part_cvo, doc.millesime, doc.labels, doc.mentions, doc.mode_de_saisie, doc.acheteur_identifiant, doc.mandataire_identifiant, doc.vendeur_identifiant, doc.valide.date_validation_acheteur, doc.valide.date_validation_mandataire, doc.valide.date_validation_vendeur, doc.valide.date_saisie, doc.date_relance, doc.vous_etes, doc.numero_contrat, vrac_version, vrac_referente, vrac_oioc_identifiant, vrac_oioc_statut, vrac_oioc_date_reception, vrac_oioc_date_traitement];
    var date_contrat = (doc.valide.date_validation)? doc.valide.date_validation : doc.valide.date_saisie;
    if (doc.mandataire_exist) {
    	emit([doc.mandataire_identifiant, date_contrat, doc._id],  vue);
    }
    emit([doc.acheteur_identifiant, date_contrat, doc._id], vue);
    emit([doc.vendeur_identifiant, date_contrat, doc._id], vue);
}