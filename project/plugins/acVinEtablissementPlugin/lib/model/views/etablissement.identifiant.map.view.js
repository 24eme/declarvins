function(doc) {
  	if (doc.type == "Etablissement") {
  		if (doc.siret) {
  			emit([doc.siret], doc._id);
  		}
  		if (doc.cvi) {
  			emit([doc.cvi], doc._id);
  		}
  		if (doc.no_accises) {
  			emit([doc.no_accises], doc._id);
  		}
	}
}
