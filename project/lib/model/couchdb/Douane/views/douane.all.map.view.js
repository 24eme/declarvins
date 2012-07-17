function(doc) {
  	if (doc.type == "Douane") {
 		emit(doc.id, null);
 	}
}