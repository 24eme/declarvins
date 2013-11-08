function(doc) {
    if (!doc.numero_archive) {
        return;
    }
   
	campagne_archive = doc.campagne;
 
	if(doc.campagne_archive) {
		campagne_archive = doc.campagne_archive;
	}

    emit([doc.type, campagne_archive, doc.numero_archive], 1)
}