function(doc) { 
	if (doc.type == "Vrac" && doc.valide.date_validation) { 

		var dateEnglishFormat = function(date) {
			if (!date) {
				return null;
			}
			var d = date.split('/');
			return d[2]+'-'+d[1]+'-'+d[0];
		}

		var getCampagne = function(date) {
			if (!date) {
				return null;
			}
			var d = date.split('-');
			var annee = parseInt(d[0]);
			var mois = d[1];
			if (mois < 8) {
				annee--;
			}
			return annee+""+(annee+1);
		}

		var getMois = function(date) {
			if (!date) {
				return null;
			}
			var d = date.split('-');
			return d[1];
		}

		var getAnnee = function(date) {
			if (!date) {
				return null;
			}
			var d = date.split('-');
			return d[0];
		}

		var vrac_campagne = getCampagne(doc.valide.date_validation);		
		var vrac_id = doc.numero_contrat;
		var vrac_version = (doc.version)? doc.version : null;
		var vrac_referente = (doc.referente === null || doc.referente === undefined)? 1 : doc.referente;
		var vrac_date_saisie = doc.valide.date_saisie;
		var vrac_date_stat = (doc.date_stats)? doc.date_stats : doc.valide.date_validation;
		var vrac_date_stat_mois = getMois(vrac_date_stat);
		var vrac_date_stat_annee = getAnnee(vrac_date_stat);
		var vrac_date_signature = (doc.date_signature)? doc.date_signature : doc.valide.date_validation;
		var vrac_mode = doc.mode_de_saisie;
		var vrac_date_validation = doc.valide.date_validation;
		var vrac_acheteur_id = doc.acheteur_identifiant;
		var vrac_acheteur_cvi = doc.acheteur.cvi;
		var vrac_acheteur_siret = doc.acheteur.siret;
		var vrac_acheteur_nom = doc.acheteur.raison_sociale;
		var vrac_acheteur_ea = doc.acheteur.num_accise;
		var vrac_vendeur_id = doc.vendeur_identifiant;
		var vrac_vendeur_cvi = doc.vendeur.cvi;
		var vrac_vendeur_siret = doc.vendeur.siret;
		var vrac_vendeur_nom = doc.vendeur.raison_sociale;
		var vrac_vendeur_ea = doc.vendeur.num_accise;
		var vrac_mandataire_id = doc.mandataire_identifiant;
		var vrac_mandataire_siret = doc.mandataire.siret;
		var vrac_mandataire_nom = doc.mandataire.raison_sociale;
		var vrac_type_transaction = doc.type_transaction;
		var vrac_produit_appellation_libelle = doc.produit_detail.appellation.libelle;
		var vrac_produit_appellation_code = doc.produit_detail.appellation.code;
		var vrac_produit_genre_libelle = doc.produit_detail.genre.libelle;
		var vrac_produit_genre_code = doc.produit_detail.genre.code;
		var vrac_produit_certification_libelle = doc.produit_detail.certification.libelle;
		var vrac_produit_certification_code = doc.produit_detail.certification.code;
		var vrac_produit_lieu_libelle = doc.produit_detail.lieu.libelle;
		var vrac_produit_lieu_code = doc.produit_detail.lieu.code;
		var vrac_produit_couleur_libelle = doc.produit_detail.couleur.libelle;
		var vrac_produit_couleur_code = doc.produit_detail.couleur.code;
		var vrac_produit_cepage_libelle = doc.produit_detail.cepage.libelle;
		var vrac_produit_cepage_code = doc.produit_detail.cepage.code;
		var vrac_millesime = doc.millesime;
		var vrac_labels_libelle = doc.labels_libelle;
		var vrac_labels = '';
		if (doc.labels_arr && doc.labels_arr.length > 0) {
			var counter = 0;
			for (l in doc.labels_arr) {
				counter++;
				vrac_labels = vrac_labels + doc.labels_arr[l];
				if (counter != doc.labels_arr.length) {
					vrac_labels = vrac_labels + '|';
				}
			}
		} else {
			vrac_labels = doc.labels;
		}
		var vrac_mentions = '';
		if (doc.mentions.length > 0) {
			var counter = 0;
			for (m in doc.mentions) {
				counter++;
				vrac_mentions = vrac_mentions + doc.mentions[m];
				if (counter != doc.mentions.length) {
					vrac_mentions = vrac_mentions + '|';
				}
			}
		}
		var vrac_mentions_libelle = doc.mentions_libelle;
		var vrac_cas_particulier = doc.cas_particulier;
		var vrac_premiere_mise_en_marche = (doc.premiere_mise_en_marche)? 1 : 0;
		var vrac_annexe = (doc.annexe)? 1 : 0;
		var vrac_volume_propose = doc.volume_propose;
		var vrac_prix_unitaire = doc.prix_unitaire;
		var vrac_type_prix = doc.type_prix;
		var vrac_determination_prix = doc.determination_prix;
		var vrac_determination_prix_date = doc.determination_prix_date;
		var vrac_export = (doc.export)? 1 : 0;
		var vrac_conditions_paiement = doc.conditions_paiement;
		var vrac_reference_contrat_pluriannuel = doc.reference_contrat_pluriannuel;
		var vrac_vin_livre = doc.vin_livre;
		var vrac_date_debut_retiraison = doc.date_debut_retiraison;
		var vrac_date_limite_retiraison = doc.date_limite_retiraison;
		var nbItem = doc.paiements.length;
		var vrac_paiements_date = '';
		var vrac_paiements_volume = '';
		var vrac_paiements_montant = '';
		if (nbItem > 0) {
			var counter = 0;
			for (p in doc.paiements) {
				counter++;
				vrac_paiements_date = vrac_paiements_date + doc.paiements[p].date;
				vrac_paiements_volume = vrac_paiements_volume + doc.paiements[p].volume;
				vrac_paiements_montant = vrac_paiements_montant + doc.paiements[p].montant;
				if (counter != nbItem) {
					vrac_paiements_date = vrac_paiements_date + '|';
					vrac_paiements_volume = vrac_paiements_volume + '|';
					vrac_paiements_montant = vrac_paiements_montant + '|';
				}
			}
		}
		var vrac_statut = doc.valide.statut;
		var regexp = new RegExp("(\r\n|\r|\n)", "g");
		var vrac_commentaire = (doc.commentaires)? (doc.commentaires).replace(regexp, " ") : null;
		var vrac_observation = (doc.observations)? (doc.observations).replace(regexp, " ") : null;

		var vrac_bailleur_metayer = (doc.bailleur_metayer)? 1 : 0;
		var vrac_oioc_date_reception = null;
		var vrac_oioc_date_traitement = null;
		if (doc.oioc) {
			vrac_oioc_date_reception = (doc.oioc.date_reception)? doc.oioc.date_reception : null;
			vrac_oioc_date_traitement = (doc.oioc.date_traitement)? doc.oioc.date_traitement : null;
		}
		
		var vrac_addr_stockage_siret = doc.adresse_stockage.siret;
		var vrac_addr_stockage_libelle = doc.adresse_stockage.libelle;
		var vrac_addr_stockage_adresse = doc.adresse_stockage.adresse;
		var vrac_addr_stockage_code_postal = doc.adresse_stockage.code_postal;
		var vrac_addr_stockage_commune = doc.adresse_stockage.commune;
		var vrac_addr_stockage_pays = doc.adresse_stockage.pays;

		var vrac_addr_stockage_has = 0;
		if (vrac_addr_stockage_adresse || vrac_addr_stockage_code_postal || vrac_addr_stockage_commune) {
			vrac_addr_stockage_has = 1;
		}
		
		var vrac_prix_total = (vrac_prix_unitaire * vrac_volume_propose).toFixed(2);
		
		var nbItem = doc.lots.length;
		
		if (nbItem > 0) {
			for (l in doc.lots) {
				var vrac_lot_numero = doc.lots[l].numero;
				var vrac_lot_assemblage = (doc.lots[l].assemblage)? 1 : 0;
				var vrac_lot_degre = doc.lots[l].degre;
				var vrac_lot_presence_allergenes = (doc.lots[l].presence_allergenes)? 1 : 0;
				var vrac_lot_bailleur = doc.lots[l].bailleur;
				
				var nbItem = doc.lots[l].cuves.length;
				var vrac_lot_cuves_numero = '';
				var vrac_lot_cuves_volume = '';
				var vrac_lot_cuves_date = '';
				if (nbItem > 0) {
					var counter = 0;
					for (c in doc.lots[l].cuves) {
						counter++;
						vrac_lot_cuves_numero = vrac_lot_cuves_numero + doc.lots[l].cuves[c].numero;
						vrac_lot_cuves_volume = vrac_lot_cuves_volume + doc.lots[l].cuves[c].volume;
						vrac_lot_cuves_date = vrac_lot_cuves_date + doc.lots[l].cuves[c].date;
						if (counter != nbItem) {
							vrac_lot_cuves_numero = vrac_lot_cuves_numero + '|';
							vrac_lot_cuves_volume = vrac_lot_cuves_volume + '|';
							vrac_lot_cuves_date = vrac_lot_cuves_date + '|';
						}
					}
				}
				var nbItem = doc.lots[l].millesimes.length;
				var vrac_lot_millesimes_annee = '';
				var vrac_lot_millesimes_pourcentage = '';
				if (nbItem > 0) {
					var counter = 0;
					for (m in doc.lots[l].millesimes) {
						counter++;
						vrac_lot_millesimes_annee = vrac_lot_millesimes_annee + doc.lots[l].millesimes[m].annee;
						vrac_lot_millesimes_pourcentage = vrac_lot_millesimes_pourcentage + doc.lots[l].millesimes[m].pourcentage;
						if (counter != nbItem) {
							vrac_lot_millesimes_annee = vrac_lot_millesimes_annee + '|';
							vrac_lot_millesimes_pourcentage = vrac_lot_millesimes_pourcentage + '|';
						}
					}
				}
				
				emit([doc.interpro, doc.valide.date_validation, doc._id, doc.produit], 
                		[vrac_id,
                		 vrac_date_stat,
                		 vrac_date_signature,
                		 vrac_acheteur_id,
                		 vrac_acheteur_cvi,
				         vrac_acheteur_siret,
                		 vrac_acheteur_nom,
                		 vrac_vendeur_id,
                		 vrac_vendeur_cvi,
                		 vrac_vendeur_siret,
                		 vrac_vendeur_nom,
                		 vrac_mandataire_id,
                		 vrac_mandataire_siret,
                		 vrac_mandataire_nom,
                		 vrac_type_transaction,
                		 vrac_produit_certification_libelle,
                		 vrac_produit_certification_code,
                		 vrac_produit_genre_libelle,
                		 vrac_produit_genre_code,
                		 vrac_produit_appellation_libelle,
                		 vrac_produit_appellation_code,
                		 vrac_produit_lieu_libelle,
                		 vrac_produit_lieu_code,
                		 vrac_produit_couleur_libelle,
                		 vrac_produit_couleur_code,
                		 vrac_produit_cepage_libelle,
                		 vrac_produit_cepage_code,
                		 vrac_millesime,
                		 vrac_millesime,
                		 vrac_labels_libelle,
                		 vrac_labels,
                		 vrac_mentions_libelle,
                		 vrac_mentions,
                		 vrac_cas_particulier,
                		 vrac_premiere_mise_en_marche,
                		 vrac_annexe,
                		 vrac_volume_propose,
                		 vrac_prix_unitaire,
                		 vrac_type_prix,
                		 vrac_determination_prix,
                		 vrac_export,
                		 vrac_conditions_paiement,
                		 vrac_reference_contrat_pluriannuel,
                		 vrac_vin_livre,
                		 vrac_date_debut_retiraison,
                		 vrac_date_limite_retiraison,
                		 vrac_paiements_date,
                		 null,
                		 vrac_paiements_montant,
                		 vrac_lot_numero,
                		 vrac_lot_cuves_numero,
                		 vrac_lot_cuves_volume,
                		 vrac_lot_cuves_date,
                		 vrac_lot_assemblage,
                		 vrac_lot_millesimes_annee,
                		 vrac_lot_millesimes_pourcentage,
                		 vrac_lot_degre,
                		 vrac_lot_presence_allergenes,
                		 vrac_statut,
                		 vrac_commentaire,
                		 vrac_version,
                		 vrac_referente,
                		 vrac_mode,
				 		 vrac_date_saisie,
				 		 vrac_date_validation,
				 		 vrac_observation,
				 		 vrac_bailleur_metayer,
				 		 doc.valide.date_validation,
				 		 vrac_oioc_date_reception,
				 		 vrac_oioc_date_traitement,
				 		 vrac_addr_stockage_siret,
				 		 vrac_addr_stockage_libelle,
				 		 vrac_addr_stockage_adresse,
				 		 vrac_addr_stockage_code_postal,
				 		 vrac_addr_stockage_commune,
				 		 vrac_addr_stockage_pays,
				 		 vrac_addr_stockage_has,
				 		 vrac_acheteur_ea,
				 		 vrac_vendeur_ea,
				 		 vrac_determination_prix_date,
				 		 vrac_campagne,
						 vrac_prix_total,
						 vrac_date_stat_mois,
						 vrac_date_stat_annee,
						 doc.type_retiraison,
						 doc.delai_paiement
                		 ]);
			}
		} else {
			emit([doc.interpro, doc.valide.date_validation, doc._id, doc.produit], 
            		[vrac_id,
            		 vrac_date_stat,
            		 vrac_date_signature,
            		 vrac_acheteur_id,
            		 vrac_acheteur_cvi,
			 		 vrac_acheteur_siret,
            		 vrac_acheteur_nom,
            		 vrac_vendeur_id,
            		 vrac_vendeur_cvi,
            		 vrac_vendeur_siret,
            		 vrac_vendeur_nom,
            		 vrac_mandataire_id,
            		 vrac_mandataire_siret,
            		 vrac_mandataire_nom,
            		 vrac_type_transaction,
            		 vrac_produit_certification_libelle,
            		 vrac_produit_certification_code,
            		 vrac_produit_genre_libelle,
            		 vrac_produit_genre_code,
            		 vrac_produit_appellation_libelle,
            		 vrac_produit_appellation_code,
            		 vrac_produit_lieu_libelle,
            		 vrac_produit_lieu_code,
            		 vrac_produit_couleur_libelle,
            		 vrac_produit_couleur_code,
            		 vrac_produit_cepage_libelle,
            		 vrac_produit_cepage_code,
            		 vrac_millesime,
            		 vrac_millesime,
            		 vrac_labels_libelle,
            		 vrac_labels,
            		 vrac_mentions_libelle,
            		 vrac_mentions,
            		 vrac_cas_particulier,
            		 vrac_premiere_mise_en_marche,
            		 vrac_annexe,
            		 vrac_volume_propose,
            		 vrac_prix_unitaire,
            		 vrac_type_prix,
            		 vrac_determination_prix,
            		 vrac_export,
            		 vrac_conditions_paiement,
            		 vrac_reference_contrat_pluriannuel,
            		 vrac_vin_livre,
            		 vrac_date_debut_retiraison,
            		 vrac_date_limite_retiraison,
            		 vrac_paiements_date,
            		 null,
            		 vrac_paiements_montant,
            		 null,
            		 null,
            		 null,
            		 null,
            		 null,
            		 null,
            		 null,
            		 null,
            		 null,
            		 vrac_statut,
            		 vrac_commentaire,
            		 vrac_version,
            		 vrac_referente,
            		 vrac_mode,
			 		 vrac_date_saisie,
			 		 vrac_date_validation,
			 		 vrac_observation,
			 		 vrac_bailleur_metayer,
			 		 doc.valide.date_validation,
			 		 vrac_oioc_date_reception,
			 		 vrac_oioc_date_traitement,
			 		 vrac_addr_stockage_siret,
			 		 vrac_addr_stockage_libelle,
			 		 vrac_addr_stockage_adresse,
			 		 vrac_addr_stockage_code_postal,
			 		 vrac_addr_stockage_commune,
			 		 vrac_addr_stockage_pays,
			 		 vrac_addr_stockage_has,
			 		 vrac_acheteur_ea,
			 		 vrac_vendeur_ea,
			 		 vrac_determination_prix_date,
			 		 vrac_campagne,
					 vrac_prix_total,
					 vrac_date_stat_mois,
					 vrac_date_stat_annee,
					 doc.type_retiraison,
					 doc.delai_paiement
            		 ]);
		}                              
	} 
}