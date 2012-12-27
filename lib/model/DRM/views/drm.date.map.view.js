function(doc) { 
	if (doc.type == "DRM" && doc.valide.date_saisie) { 
		

		var explodeIdDRM = function(id) {
			return id.split('-');
		}
		var getAnneeByDRM = function (tabId) {
			if (typeof tabId[2] != "undefined") {
				return tabId[2];
			} else {
				return null;
			}
		}
		var getMoisByDRM = function (tabId) {
			if (typeof tabId[3] != "undefined") {
				return tabId[3];
			} else {
				return null;
			}
		}
		var getVersionByDRM = function (tabId) {
			if (typeof tabId[4] != "undefined") {
				return tabId[4];
			} else {
				return null;
			}
		}
		
		var key = 'DETAIL';
		var drm_id = doc._id;
		var drm_campagne = (doc.campagne).replace("-", "");
		var drm_explosed_id = explodeIdDRM(drm_id);
		var drm_precedente_explosed_id = explodeIdDRM(doc.precedente);
		var drm_identifiant = doc.identifiant;
		var drm_declarant = doc.declarant.raison_sociale;
		var drm_annee = getAnneeByDRM(drm_explosed_id);
		var drm_mois = getMoisByDRM(drm_explosed_id);
		var drm_version = doc.version;
		var drm_precedente_annee = getAnneeByDRM(drm_precedente_explosed_id);
		var drm_precedente_mois = getMoisByDRM(drm_precedente_explosed_id);
		var drm_precedente_version = getVersionByDRM(drm_precedente_explosed_id);
		var drm_date_saisie = doc.valide.date_saisie;
		var drm_date_signee = doc.valide.date_signee;
		var drm_mode_saisie = doc.mode_de_saisie;
		
		for(interpro in doc.interpros) {
			for(certification_key in doc.declaration.certifications) {
			    var certification = doc.declaration.certifications[certification_key];
			    var certification_hash = "declaration/certifications/"+certification_key;
			    var certification_code = certification_key;
			    var certification_libelle = certification_key;
			    for(genre_key in certification.genres) {
			        var genre = certification.genres[genre_key];
			        var genre_hash = certification_hash+"/genres/"+genre_key;
				    var genre_code = genre.code;
				    var genre_libelle = genre.libelle;
			        for(appellation_key in genre.appellations) {
			            var appellation = genre.appellations[appellation_key];
			            var appellation_hash = genre_hash+"/appellations/"+appellation_key;
					    var appellation_code = appellation.code;
					    var appellation_libelle = appellation.libelle;
			            for(mention_key in appellation.mentions) {
			                var mention = appellation.mentions[mention_key];
			                var mention_hash = appellation_hash+"/mentions/"+appellation_key;
						    var mention_code = mention.code;
						    var mention_libelle = mention.libelle;
			                for(lieu_key in mention.lieux) {
			                    var lieu = mention.lieux[lieu_key];
			                    var lieu_hash = mention_hash+"/lieux/"+lieu_key;
							    var lieu_code = lieu.code;
							    var lieu_libelle = lieu.libelle;
			                    for(couleur_key in lieu.couleurs) {
			                        var couleur = lieu.couleurs[couleur_key];
			                        var couleur_hash = lieu_hash+"/couleurs/"+couleur_key;
								    var couleur_code = couleur.code;
								    var couleur_libelle = couleur.libelle;
			                        for(cepage_key in couleur.cepages) {
			                            var cepage = couleur.cepages[cepage_key];
			                            var cepage_hash = couleur_hash+"/cepages/"+cepage_key;
									    var cepage_code = cepage.code;
									    var cepage_libelle = cepage.libelle;
			                            for(detail_key in cepage.details) {
			                                var detail = cepage.details[detail_key];
			                                var detail_hash =  cepage_hash+"/details/"+detail_key;
			                                var montant_cvo = parseFloat(detail.cvo.taux) * parseFloat(detail.cvo.volume_taxable);
			                                if (isNaN(montant_cvo)) {
			                                	montant_cvo = null;
			                                }
			                                var libelles_label = null;
			                                var codes_label = null;
			                                var counter = 0;
			                                var nb_labels = (detail.libelles_label).length;
			                                for (label_key in detail.libelles_label) {
			                                	counter++;
			                                	libelles_label += detail.libelles_label[label_key];
			                                	codes_label += label_key;
			                                	if (counter < nbLabels) {
			                                		libelles_label += '|';
			                                		codes_label += '|';
			                                	}
			                                }
			                                emit([doc.interpros[interpro], doc.valide.date_saisie, doc._id, detail_hash], 
			                                		[key,
			                                		 drm_identifiant,
			                                		 drm_declarant,
			                                		 drm_annee,
			                                		 drm_mois,
			                                		 drm_version,
			                                		 drm_precedente_annee,
			                                		 drm_precedente_mois,
			                                		 drm_precedente_version,
			                                		 certification_libelle,
			                                		 certification_code,
			                                		 genre_libelle,
			                                		 genre_code,
			                                		 appellation_libelle,
			                                		 appellation_code,
			                                		 lieu_libelle,
			                                		 lieu_code,
			                                		 couleur_libelle,
			                                		 couleur_code,
			                                		 cepage_libelle,
			                                		 cepage_code,
			                                		 detail.millesime,
			                                		 null,
			                                		 libelles_label,
			                                		 codes_label,
			                                		 detail.label_supplementaire,
			                                		 detail.total_debut_mois,
			                                		 detail.stocks_debut.bloque,
			                                		 detail.stocks_debut.warrante,
			                                		 detail.stocks_debut.instance,
			                                		 detail.stocks_debut.commercialisable,
			                                		 detail.total_entrees,
			                                		 detail.entrees.achat,
			                                		 detail.entrees.recolte,
			                                		 detail.entrees.repli,
			                                		 detail.entrees.declassement,
			                                		 detail.entrees.mouvement,
			                                		 detail.entrees.crd,
			                                		 detail.total_sorties,
			                                		 detail.sorties.vrac,
			                                		 detail.sorties.export,
			                                		 detail.sorties.factures,
			                                		 detail.sorties.crd,
			                                		 detail.sorties.consommation,
			                                		 detail.sorties.pertes,
			                                		 detail.sorties.declassement,
			                                		 detail.sorties.repli,
			                                		 detail.sorties.mouvement,
			                                		 detail.sorties.distillation,
			                                		 detail.sorties.lies,
			                                		 detail.total,
			                                		 detail.stocks_fin.bloque,
			                                		 detail.stocks_fin.warrante,
			                                		 detail.stocks_fin.instance,
			                                		 detail.stocks_fin.commercialisable,
			                                		 drm_date_saisie,
			                                		 drm_date_signee,
			                                		 drm_mode_saisie,
			                                		 detail.cvo.code,
			                                		 detail.cvo.taux,
			                                		 detail.cvo.volume_taxable,
			                                		 montant_cvo,
			                                		 drm_campagne,
			                                		 drm_id = doc._id
			                                		 ]
			                                );
			                            }
			                        }
			                    }
			                }
			            }
			        }
			    }
			}
		}
	} 
}