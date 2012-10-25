function(doc) {
    if (!doc.numero_archive) {
        return;
    }

    emit([doc.type, doc.numero_archive], 1)
}