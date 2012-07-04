function(doc) {
  	if (doc.type != "Vrac") {
    	return;
    }
  	
  	emit([doc.mandataire_identifiant, doc._id],  [doc.status, doc._id, doc.acheteur_identifiant, doc.acheteur.nom, doc.vendeur_identifiant, doc.vendeur.nom, doc.mandataire_identifiant,doc.mandataire.nom, doc.type_transaction, doc.produit, doc.volume_consomme, doc.volume_enleve]);
  	
  	emit([doc.acheteur_identifiant, doc._id], [doc.status, doc._id, doc.acheteur_identifiant, doc.acheteur.nom, doc.vendeur_identifiant, doc.vendeur.nom, doc.mandataire_identifiant,doc.mandataire.nom, doc.type_transaction, doc.produit, doc.volume_consomme, doc.volume_enleve]);
  	
  	emit([doc.vendeur_identifiant, doc._id], [doc.status, doc._id, doc.acheteur_identifiant, doc.acheteur.nom, doc.vendeur_identifiant, doc.vendeur.nom, doc.mandataire_identifiant,doc.mandataire.nom, doc.type_transaction, doc.produit, doc.volume_consomme, doc.volume_enleve]);
}