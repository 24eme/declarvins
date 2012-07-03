function(doc) {
  	if (doc.type == "Etablissement") {
  		emit([doc.interpro, 
  			  doc.famille, 
  			  doc._id, 
  			  doc.nom, 
		  	  doc.identifiant, 
		  	  doc.raison_sociale, 
		  	  doc.siret, 
		  	  doc.cvi, 
		  	  doc.siege.commune, 
		      doc.siege.code_postal], null);
 	}
}