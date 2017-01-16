function(doc) { 
	if (doc.type == "Vrac" && doc.valide.date_validation) {
		var re = new RegExp('\/declaration\/certifications\/([A-Za-z0-9]+)\/genres\/([A-Za-z0-9]+)\/appellations\/([A-Za-z0-9]+)\/mentions\/([A-Za-z0-9]+)\/lieux\/([A-Za-z0-9]+)\/couleurs\/([A-Za-z0-9]+)\/cepages\/([A-Za-z0-9]+)', 'g');
		var codesProduit = re.exec(doc.produit);
		
		var produit = doc.produit_libelle;
		if (doc.produit_detail.codes.inao) {
			produit += '('+doc.produit_detail.codes.inao+')';
		} else if (doc.produit_detail.codes.libelle_fiscal) {
			produit += '('+doc.produit_detail.codes.libelle_fiscal+')';
		}
		
		var vendeur = doc.vendeur_identifiant;
		if (doc.vendeur.siret) {
			vendeur += '('+doc.vendeur.siret+')';
		} else if (doc.vendeur.cvi) {
			vendeur += '('+doc.vendeur.cvi+')';
		}
		
		var acheteur = doc.acheteur.raison_sociale;
		if (!acheteur) {
			acheteur = doc.acheteur.nom;
		} else if (doc.acheteur.nom && doc.acheteur.nom != doc.acheteur.raison_sociale) {
			acheteur += ' - '+doc.acheteur.nom;
		}
		

		for(zone in doc.vendeur.zones) {
			emit(
				[zone, doc.valide.statut], 
            	[
            	 	doc.numero_contrat,
            	 	vendeur,
            	 	doc.vendeur.num_accise,
            	 	acheteur,
            	 	(codesProduit[1] && codesProduit[1] != 'DEFAUT')? codesProduit[1] : null,
            	 	(codesProduit[2] && codesProduit[2] != 'DEFAUT')? codesProduit[2] : null,
            	 	(codesProduit[3] && codesProduit[3] != 'DEFAUT')? codesProduit[3] : null,
            	 	(codesProduit[4] && codesProduit[4] != 'DEFAUT')? codesProduit[4] : null,
                    (codesProduit[5] && codesProduit[5] != 'DEFAUT')? codesProduit[5] : null,
                    (codesProduit[6] && codesProduit[6] != 'DEFAUT')? codesProduit[6] : null,
                    (codesProduit[7] && codesProduit[7] != 'DEFAUT')? codesProduit[7] : null,
            	 	produit,
            	 	doc.millesime,
            	 	doc.volume_propose,
            	 	doc.volume_enleve
            	 ]
			);
		}                           
	} 
}