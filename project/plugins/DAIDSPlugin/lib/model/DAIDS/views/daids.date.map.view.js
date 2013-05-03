function(doc) { 
	if (doc.type == "DAIDS" && doc.valide.date_saisie) { 

		var explodeIdDAIDS = function(id) {
			return id.split('-');
		}
		var getAnneeDebutByDAIDS = function (tabId) {
			if (typeof tabId[2] != "undefined") {
				return tabId[2];
			} else {
				return null;
			}
		}
		var getAnneeFinByDAIDS = function (tabId) {
			if (typeof tabId[3] != "undefined") {
				return tabId[3];
			} else {
				return null;
			}
		}
		var getVersionByDAIDS = function (tabId) {
			if (typeof tabId[4] != "undefined") {
				return tabId[4];
			} else {
				return null;
			}
		}
		
		var key = 'DETAIL';
		var key_default = 'DEFAUT';
		var daids_id = doc._id;
		var daids_campagne = (doc.campagne).replace("-", "");
		var daids_explosed_id = explodeIdDAIDS(daids_id);
		var daids_precedente_annee_debut = null;
		var daids_precedente_annee_fin = null;
		var daids_precedente_version = null;
		if (doc.precedente) {
			daids_precedente_explosed_id = explodeIdDAIDS(doc.precedente);
			daids_precedente_annee_debut = getAnneeDebutByDAIDS(daids_precedente_explosed_id);
			daids_precedente_annee_fin = getAnneeFinByDAIDS(daids_precedente_explosed_id);
			daids_precedente_version = getVersionByDAIDS(daids_precedente_explosed_id);
		}
		var daids_identifiant = doc.identifiant;
		var daids_declarant = doc.declarant.raison_sociale;
		var daids_annee_debut = getAnneeDebutByDAIDS(daids_explosed_id);
		var daids_annee_fin = getAnneeFinByDAIDS(daids_explosed_id);
		var daids_version = doc.version;
		var daids_date_saisie = doc.valide.date_saisie;
		var daids_date_signee = doc.valide.date_signee;
		var daids_mode_saisie = doc.mode_de_saisie;
		var daids_identifiant_daids_historique = doc.identifiant_daids_historique;
		var daids_identifiant_ivse = doc.identifiant_ivse;
		
		for(interpro in doc.interpros) {
			for(certification_key in doc.declaration.certifications) {
			    var certification = doc.declaration.certifications[certification_key];
			    var certification_hash = "declaration/certifications/"+certification_key;
			    var certification_code = certification_key;
			    var certification_libelle = certification_key;
			    for(genre_key in certification.genres) {
			        var genre = certification.genres[genre_key];
			        var genre_hash = certification_hash+"/genres/"+genre_key;
				var genre_code = genre_key;
				var genre_libelle = genre.libelle;
		        if (genre_code == key_default) {
		        	genre_code = null;
		        	genre_libelle = null;
		        }
			        for(appellation_key in genre.appellations) {
			            var appellation = genre.appellations[appellation_key];
			            var appellation_hash = genre_hash+"/appellations/"+appellation_key;
				    var appellation_code = appellation_key;
				    var appellation_libelle = appellation.libelle;
			        if (appellation_code == key_default) {
			        	appellation_code = null;
			        	appellation_libelle = null;
			        }
			            for(mention_key in appellation.mentions) {
			                var mention = appellation.mentions[mention_key];
			                var mention_hash = appellation_hash+"/mentions/"+appellation_key;
				        var mention_code = mention_key;
					var mention_libelle = mention.libelle;
			        if (mention_code == key_default) {
			        	mention_code = null;
			        	mention_libelle = null;
			        }
			                for(lieu_key in mention.lieux) {
			                    var lieu = mention.lieux[lieu_key];
			                    var lieu_hash = mention_hash+"/lieux/"+lieu_key;
					    var lieu_code = lieu_key;
					    var lieu_libelle = lieu.libelle;
				        if (lieu_code == key_default) {
				        	lieu_code = null;
				        	lieu_libelle = null;
				        }
			                    for(couleur_key in lieu.couleurs) {
			                        var couleur = lieu.couleurs[couleur_key];
			                        var couleur_hash = lieu_hash+"/couleurs/"+couleur_key;
						var couleur_code = couleur_key;
						var couleur_libelle = couleur.libelle;
				        if (couleur_code == key_default) {
				        	couleur_code = null;
				        	couleur_libelle = null;
				        }
			                        for(cepage_key in couleur.cepages) {
			                            var cepage = couleur.cepages[cepage_key];
			                            var cepage_hash = couleur_hash+"/cepages/"+cepage_key;
						    var cepage_code = cepage_key;
						    var cepage_libelle = cepage.libelle;
					        if (cepage_code == key_default) {
					        	cepage_code = null;
					        	cepage_libelle = null;
					        }
			                            for(detail_key in cepage.details) {
			                                var detail = cepage.details[detail_key];
			                                var detail_hash =  cepage_hash+"/details/"+detail_key;
			                                var libelles_label = null;
			                                var codes_label = null;
			                                var counter = 0;
			                                var nb_labels = (detail.libelles_label).length;
							if (nb_labels > 0) {
			                                for (label_key in detail.libelles_label) {
			                                	counter++;
			                                	libelles_label += detail.libelles_label[label_key];
			                                	codes_label += label_key;
			                                	if (counter < nb_labels) {
			                                		libelles_label += '|';
			                                		codes_label += '|';
			                                	}
			                                }
							}
			                                emit([doc.interpros[interpro], doc.valide.date_saisie, doc._id, detail_hash], 
			                                		[key,
			                                		 daids_identifiant,
			                                		 daids_declarant,
			                                		 daids_annee_debut,
			                                		 daids_annee_fin,
			                                		 daids_version,
			                                		 daids_precedente_annee_debut,
			                                		 daids_precedente_annee_fin,
			                                		 daids_precedente_version,
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
			                                		 detail.stock_theorique,
			                                		 detail.stock_chais,
			                                		 detail.stocks.inventaire_chais,
			                                		 detail.chais_details.entrepot_a,
			                                		 detail.chais_details.entrepot_b,
			                                		 detail.chais_details.entrepot_c,
			                                		 detail.stocks.propriete_tiers,
			                                		 detail.stock_propriete,
			                                		 detail.stocks.physique_chais,
			                                		 detail.stocks.tiers,
			                                		 detail.stock_propriete_details.reserve,
			                                		 detail.stock_propriete_details.conditionne,
			                                		 detail.stock_propriete_details.vrac_vendu,
			                                		 detail.stock_propriete_details.vrac_libre,
			                                		 detail.total_manquants_excedents,
			                                		 detail.stock_mensuel_theorique,
			                                		 detail.stocks_moyen.vinifie.volume,
			                                		 detail.stocks_moyen.vinifie.taux,
			                                		 detail.stocks_moyen.vinifie.total,
			                                		 detail.stocks_moyen.non_vinifie.volume,
			                                		 detail.stocks_moyen.non_vinifie.total,
			                                		 detail.stocks_moyen.conditionne.volume,
			                                		 detail.stocks_moyen.conditionne.taux,
			                                		 detail.stocks_moyen.conditionne.total,
			                                		 detail.total_pertes_autorisees,
			                                		 detail.total_manquants_taxables,			                                		 
			                                		 daids_date_saisie,
			                                		 daids_date_signee,
			                                		 daids_mode_saisie,
			                                		 detail.cvo.code,
			                                		 detail.cvo.taux,
			                                		 detail.total_cvo,
			                                		 daids_campagne,
			                                		 daids_identifiant_daids_historique,
			                                		 daids_identifiant_ivse
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