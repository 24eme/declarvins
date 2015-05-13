function(doc) {
  	if (doc.type == "Etablissement") {
		for (var zone in doc.zones) {
  		emit([doc.statut,zone, 
  			  doc.famille, 
  			  doc.sous_famille, 
  			  doc.societe,
  			  doc._id, 
  			  doc.nom, 
		  	  doc.identifiant, 
		  	  doc.raison_sociale, 
		  	  doc.siret, 
		  	  doc.cvi, 
		  	  doc.siege.commune, 
		      	  doc.siege.code_postal,
		      	  doc.siege.pays,
		      	  doc.statut,
			  doc.contrat_mandat,
    			  doc.service_douane], null);
 	}
	}
}
