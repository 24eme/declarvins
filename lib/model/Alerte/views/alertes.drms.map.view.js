function(doc) {
  	if (doc.type == "DRM") {
  		emit([doc.identifiant, (doc.periode).substring(0, 4), (doc.periode).substring(5, 7)], null);
 	}
}