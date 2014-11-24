function(doc) {
  	if (doc.type == "DRM" && !doc.version) {
		for(i in doc.interpros) {

			var nom = (doc.declarant.nom)? doc.declarant.nom : "";
			var raison_sociale = (doc.declarant.raison_sociale)? doc.declarant.raison_sociale : "";
			var siret = (doc.declarant.siret)? doc.declarant.siret : "";
			var cni = (doc.declarant.cni)? doc.declarant.cni : "";
			var cvi = (doc.declarant.cvi)? doc.declarant.cvi : "";
			var no_accises = (doc.declarant.no_accises)? doc.declarant.no_accises : "";
			var no_tva_intracommunautaire = (doc.declarant.no_tva_intracommunautaire)? doc.declarant.no_tva_intracommunautaire : "";
			var adresse = (doc.declarant.siege.adresse)? doc.declarant.siege.adresse : "";
			var code_postal = (doc.declarant.siege.code_postal)? doc.declarant.siege.code_postal : "";
			var commune = (doc.declarant.siege.commune)? doc.declarant.siege.commune : "";
			var pays = (doc.declarant.siege.pays)? doc.declarant.siege.pays : "";
			var service_douane = (doc.declarant.service_douane)? doc.declarant.service_douane : "";
			var total = (doc.declaration.total)? doc.declaration.total : 0;
			var date_saisie = (doc.valide.date_saisie)? doc.valide.date_saisie : "";
			var email = (doc.declarant.email)? doc.declarant.email : "";
			var telephone = (doc.declarant.telephone)? doc.declarant.telephone : "";
			var fax = (doc.declarant.fax)? doc.declarant.fax : "";
			var igp_manquant = (doc.manquants)? doc.manquants.igp : 0;
			var contrat_manquant = (doc.manquants)? doc.manquants.contrats : 0;

	  		emit([doc.interpros[i], doc.campagne, doc.periode, doc.identifiant, doc.periode], [
			nom,
			raison_sociale,
			siret,
			cni,
			cvi,
			no_accises,
			no_tva_intracommunautaire,
			adresse,
			code_postal,
			commune,
			pays,
			service_douane,
			total,
			date_saisie,
			email,
			telephone,
			fax,
			igp_manquant,
			contrat_manquant
			]);
		}
 	}
}