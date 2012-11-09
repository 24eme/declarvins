function(doc) {
    if (doc.type != "CompteTiers") {
        return;
    }
    var numero_contrat = doc.contrat.replace("CONTRAT-", "");
    emit([numero_contrat, doc.nom, doc.prenom, doc.login, doc.email], null);

}