function(doc) {
    if (doc.type != "DAIDS") {
        
        return;     
    }
    
    emit([doc.identifiant, doc.campagne, doc.periode, doc.version, doc.mode_de_saisie, doc.valide.date_saisie, doc.douane.envoi, doc.douane.accuse], 1);
}