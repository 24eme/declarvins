function(doc) {
  	if (doc.type != "Vrac") {
 		return;
    }

	emit([doc._id], [doc.status, doc._id, doc.acheteur_identifiant, doc.acheteur.nom, doc.vendeur_identifiant, doc.vendeur.nom, doc.mandataire_identifiant,doc.mandataire.nom, doc.type_transaction, doc.produit, doc.volume_propose, doc.volume_enleve]);
}