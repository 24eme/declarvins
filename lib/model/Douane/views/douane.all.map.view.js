function(doc) {
  	if (doc.type == "Douane") {
 		emit([doc.statut], [doc.nom, doc.identifiant, doc.email, doc.statut]);
 	}
}