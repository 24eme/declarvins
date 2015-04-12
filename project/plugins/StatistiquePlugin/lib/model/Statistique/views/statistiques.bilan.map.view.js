function(doc) {
  	if (doc.type == "Bilan") {
		for (var zone in doc.etablissement.zones) {
			emit([zone, doc.etablissement.statut, doc._id],[doc]);
		}
	}
}