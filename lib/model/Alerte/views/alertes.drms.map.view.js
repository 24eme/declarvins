function(doc) {
  	if (doc.type == "DRM") {
  		emit([doc.identifiant, (doc.campagne).substring(0, 4), (doc.campagne).substring(5, 7)], null);
 	}
}