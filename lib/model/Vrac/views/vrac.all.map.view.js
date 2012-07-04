function(doc) {
	if (doc.type == 'Vrac') {
  		emit([doc.vendeur_identifiant, doc.produit, doc._id, doc.valide.statut], 1);
  	}
}
