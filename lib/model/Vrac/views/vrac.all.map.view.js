function(doc) {
	if (doc.type == 'Vrac') {
  		emit([doc.valide.statut, doc.vendeur_identifiant, doc.produit, doc._id], 1);
  	}
}
