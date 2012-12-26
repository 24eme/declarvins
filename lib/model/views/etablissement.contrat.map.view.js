function(doc) {
  	if (doc.type == "Etablissement") {
  		emit([doc.contrat_mandat], [
  			  doc._id]);
 	}
}