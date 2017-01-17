function(doc) { 
	if (doc.type == "Vrac" && doc.valide.date_validation && doc.produit) {
		var codesProduit = (doc.produit).split('/');;
		
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
            	 	(codesProduit[3] && codesProduit[3] != 'DEFAUT')? codesProduit[3] : null,
                    (codesProduit[5] && codesProduit[5] != 'DEFAUT')? codesProduit[5] : null,
                    (codesProduit[7] && codesProduit[7] != 'DEFAUT')? codesProduit[7] : null,
                    (codesProduit[9] && codesProduit[9] != 'DEFAUT')? codesProduit[9] : null,
                    (codesProduit[11] && codesProduit[11] != 'DEFAUT')? codesProduit[11] : null,
                    (codesProduit[13] && codesProduit[13] != 'DEFAUT')? codesProduit[13] : null,
                    (codesProduit[15] && codesProduit[15] != 'DEFAUT')? codesProduit[15] : null,
            	 	produit,
            	 	doc.millesime,
            	 	(doc.volume_propose).toFixed(4),
            	 	(doc.volume_enleve).toFixed(4)
            	 ]
			);
		}                           
	} 
}