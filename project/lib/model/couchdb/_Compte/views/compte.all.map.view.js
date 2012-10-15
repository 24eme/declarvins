function(doc) {
    var r = new RegExp('\^COMPTE-');
    if (!doc._id.match(r)) {

        return;
    }

    for(interpro_id in doc.interpro) {        
        emit([interpro_id, doc.type, doc.nom, doc.prenom, doc.login, doc.email, doc.telephone], null);
    }

}