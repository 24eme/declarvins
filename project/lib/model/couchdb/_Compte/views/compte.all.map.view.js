function(doc) {
    var r = new RegExp('\^COMPTE-');
    if (!doc._id.match(r)) {
        return;
    }
    var numero_contrat = (doc.contrat)? doc.contrat.replace("CONTRAT-", "") : null;
    for(interpro_id in doc.interpro) {        
        emit([interpro_id, doc.type, doc.statut], [numero_contrat, doc.nom, doc.prenom, doc.login, doc.email, doc.raison_sociale, doc.telephone, doc.oioc]);
    }
    emit([doc.contrat_valide, doc.type, doc.statut], [numero_contrat, doc.nom, doc.prenom, doc.login, doc.email, doc.raison_sociale, doc.telephone, doc.oioc]);

}