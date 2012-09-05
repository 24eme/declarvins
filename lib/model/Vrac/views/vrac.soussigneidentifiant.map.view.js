function(doc) {
    if (doc.type != "Vrac") {
            return;
        }

    var vue = [doc.valide.statut, doc._id, doc.acheteur_identifiant, doc.acheteur.nom, doc.acheteur.raison_sociale, doc.vendeur_identifiant, doc.vendeur.nom, doc.vendeur.raison_sociale, doc.mandataire_identifiant, doc.mandataire.nom, doc.mandataire.raison_sociale, doc.type_transaction, doc.produit, doc.volume_propose, doc.volume_enleve, doc.prix_total];
    
    emit([doc.mandataire_identifiant, doc._id],  vue);
    emit([doc.acheteur_identifiant, doc._id], vue);
    emit([doc.vendeur_identifiant, doc._id], vue);
}