function(doc) {
     if (doc.type != "DRM" && doc.type != 'SV12') {
 
         return;
     }
 
     if (!doc.valide.date_saisie) {
 
         return;
     }

    var nom_declarant = doc.declarant.nom
    if (! nom_declarant) {
        nom_declarant = doc.declarant.raison_sociale
    }

    nom_declarant = nom_declarant.replace(",", "")
 
     for(identifiant in doc.mouvements) {
         if(identifiant == doc.identifiant) {
             for (key in doc.mouvements[identifiant]) {
                 var mouv = doc.mouvements[identifiant][key];
                 pays = "";
                 if (mouv.type_hash.match(/^export.*_details$/)) {
                      pays = mouv.detail_libelle;
                 }
                 var coefficient_facturation = -1;
                 if(mouv.coefficient_facturation) {
                     coefficient_facturation = mouv.coefficient_facturation;
                 }
                 familledrm = "PRODUCTEUR"
                 if (doc.declarant.famille) {
                     familledrm = doc.declarant.famille;
                 }
				 type_drm = "SUSPENDU";
                 type_drm_libelle = "Suspendu";
                 if (mouv.type_hash.match(/acq_/)) {
				 	type_drm = "ACQUITTE";
                 	type_drm_libelle = "Acquitte";
                 }
                 emit([doc.type, identifiant, doc.campagne, doc.periode, doc._id, mouv.produit_hash, type_drm, mouv.type_hash, mouv.vrac_numero, mouv.detail_identifiant], [nom_declarant, mouv.produit_libelle, type_drm_libelle, mouv.type_libelle, mouv.volume, mouv.vrac_destinataire, mouv.detail_libelle, mouv.date_version, mouv.version, mouv.cvo, mouv.facturable, doc._id+'/mouvements/'+identifiant+'/'+key, pays, mouv.facture, coefficient_facturation, mouv.date, familledrm, mouv.interpro]);
             }
         }
     }
 }
