function(doc) {
    if (doc.type != "DRM") {

        return;
    }
     var transmission = "NON";
     var horodatage = null;
     var coherente = null;
     var diff = null;
     var teledeclare = 'OUI';

     if(doc.mode_de_saisie == "PAPIER") {
	teledeclare = 'NON';
     }

     if (doc.ciel) {
     	if (doc.ciel.transfere == 1) {
     		transmission = "SUCCES";
     	  	horodatage = doc.ciel.horodatage_depot;
     	}
	if (doc.ciel.transfere == 0) {
     		transmission = "ECHEC";
     	}
	if (doc.ciel.valide) {
     		coherente = "CONFORME";
	} else if (doc.ciel.diff) {
		coherente = "NON CONFORME";
	}
     }
    emit([doc.identifiant, doc.campagne, doc.periode, doc.version, doc.mode_de_saisie, doc.valide.date_saisie, doc.douane.envoi, doc.douane.accuse, doc.numero_archive, teledeclare, transmission, horodatage, coherente, diff, doc.interpros.join('|')], 1);
}
