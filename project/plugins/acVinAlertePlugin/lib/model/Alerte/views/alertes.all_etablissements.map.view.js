function(doc) {
  	if (doc.type == "Alerte") {
		var last = null;
		for(var last_alerte in doc.alertes) {
			last = doc.alertes[last_alerte];
		}
  		emit([doc.interpro, last.statut, doc.sous_type, doc.etablissement_identifiant, doc.campagne, doc._id], {"etablissement_identifiant" : doc.etablissement_identifiant, "derniere_alerte" : last, "date_derniere_alerte" : last_alerte, "derniere_detection" : doc.derniere_detection});
 	}
}