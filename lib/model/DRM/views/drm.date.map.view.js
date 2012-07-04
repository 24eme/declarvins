function(doc) { 
	if (doc.type == "DRM" && doc.valide.date_saisie) { 
		rect = null; 
		
		if (doc.rectificative) { 
			rect = doc.rectificative;
		}	 

		for(inter in doc.interpros) {
			
			emit([doc.interpros[inter], doc.valide.date_saisie, doc._id], 1);  
		}  
	} 
}