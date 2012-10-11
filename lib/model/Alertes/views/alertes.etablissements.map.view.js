function(doc) {
  	if (doc.type == "Etablissement") {
  		emit([doc.statut, doc.identifiant], null);
 	}
}