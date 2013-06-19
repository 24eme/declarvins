function(doc) {
    if (doc.type != 'OIOC') {
        return;
    }
    emit([doc._id, doc.statut], [doc._id, doc.identifiant, doc.nom]);
}