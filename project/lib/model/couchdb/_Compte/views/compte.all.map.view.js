function(doc) {
	var r = new RegExp('\^COMPTE-');
  	if (doc._id.match(r)) {
  		
  		emit([doc.type, doc.nom, doc.prenom, doc.login, doc.email, doc.telephone], null);
 	}
}