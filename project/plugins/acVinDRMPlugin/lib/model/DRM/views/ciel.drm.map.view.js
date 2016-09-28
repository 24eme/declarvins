function(doc) {
    if (doc.type != "DRM") {
        
        return;     
    }
    var ciel = (doc.ciel && doc.ciel.transfere)? 1 : 0; 
    emit([ciel, doc.declarant.no_accises, doc.periode], null);
}