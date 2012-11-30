function(doc) {
    if (doc.type != "CompteTiers") {
        return;
    }
    var numero_contrat = doc.contrat.replace("CONTRAT-", "");
    for (i in doc.interpro)
    {
      emit([i, numero_contrat, doc.nom, doc.prenom, doc.login, doc.email, doc.raison_sociale], null);
    }

}