function(doc) {
  	if (doc.type == "Bilan") {
		for (var zone in doc.etablissement.zones) {
			for (var periode in doc.periodes) {
				emit([zone, periode, doc.periodes[periode].statut, doc.etablissement.statut], [doc.identifiant, doc.etablissement]);
			}
		}
	}
}