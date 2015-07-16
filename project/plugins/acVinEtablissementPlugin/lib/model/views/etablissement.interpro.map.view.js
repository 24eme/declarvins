function(doc) {
  	if (doc.type == "Etablissement") {
  		emit([doc.siret], [doc.interpro]);
	}
}
