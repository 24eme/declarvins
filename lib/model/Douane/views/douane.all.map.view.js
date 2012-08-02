function(doc) {
  	if (doc.type == "Douane") {
 		emit(doc._id, [doc.nom, doc.identifiant, doc.email, doc.statut]);
 	}
}