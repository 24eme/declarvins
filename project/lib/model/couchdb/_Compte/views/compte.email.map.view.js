function(doc) {
    var r = new RegExp('\^COMPTE-');
    if (!doc._id.match(r)) {
        return;
    }
    emit([doc.type, doc.email], [doc.login, doc._id]);

}