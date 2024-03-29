function(doc) {
    if (doc.type == "DRM" && doc.valide.date_saisie) {

        var getCouleur = function(couleur) {
            if (couleur == "rouge") {
                return 1;
            }
            else if (couleur == "rose") {
                return 2;
            }
            else if (couleur == "blanc") {
                return 3;
            }
            else {
                return couleur;
            }

        }

        var explodeIdDRM = function(id) {
            return id.split('-');
        }
        var getAnneeByDRM = function (tabPeriode) {
            if (typeof tabPeriode[0] != "undefined") {
                return tabPeriode[0];
            } else {
                return null;
            }
        }
        var getMoisByDRM = function (tabPeriode) {
            if (typeof tabPeriode[1] != "undefined") {
                return tabPeriode[1];
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

        var groupByLastVersion = function (version) {
            var versionRegexp = RegExp("^[A-Z]{1}[0-9]+[A-Z]{1}[0-9]+$");
            if (versionRegexp.test(version)) {
                var version1 = version.substring(0,1);
                var numero1 = parseInt(version.substring(1, 3));
                var version2 = version.substring(3, 4);
                var numero2 = parseInt(version.substring(4, 6));
                var cumul = formatVersion(numero1 + numero2);
                if (numero1 > numero2) {
                    return version1+cumul;
                }
                return version2+cumul;
            }
            return version;
        }

        var formatVersion = function (version) {
            if (version < 10) {
                return '0'+version;
            }
            return ''+version;
        }

        var key = 'DETAIL';
        var key_vrac = 'CONTRAT';
        var key_default = 'DEFAUT';
        var drm_id = doc._id;
        var drm_campagne = (doc.campagne).replace("-", "");
        var drm_explosed_id = explodeIdDRM(drm_id);
        var drm_explosed_periode = explodeIdDRM(doc.periode);
        var drm_precedente_explosed_id = null;
        var drm_precedente_annee = null;
        var drm_precedente_mois = null;
        var drm_precedente_version = null;
        var drm_identifiant = doc.identifiant;
        var drm_declarant = doc.declarant.raison_sociale;
        var drm_declarant_famille = doc.declarant.famille;
        var drm_declarant_sousfamille = doc.declarant.sous_famille;
        var drm_annee = getAnneeByDRM(drm_explosed_periode);
        var drm_mois = getMoisByDRM(drm_explosed_periode);
        var drm_version = groupByLastVersion(doc.version);
        var drm_date_saisie = doc.valide.date_saisie;
        var drm_date_signee = doc.valide.date_signee;
        var drm_mode_saisie = doc.mode_de_saisie;
        var drm_identifiant_drm_historique = doc.identifiant_drm_historique;
        if (!drm_identifiant_drm_historique) {
            if (doc.version) {
                drm_identifiant_drm_historique = 'DRM-'+drm_identifiant+'-'+drm_annee+'-'+drm_mois+'-'+drm_version;
            } else {
                drm_identifiant_drm_historique = doc._id+'-000';
            }
        }
        var drm_identifiant_ivse = doc.identifiant_ivse;
        var drm_referente = (doc.referente)? doc.referente : 0;
        var drm_contrats_manquants = (doc.manquants)? doc.manquants.contrats : null;
        var drm_igp_manquants = (doc.manquants)? doc.manquants.igp : null;

        var regexp = new RegExp("(\r\n|\r|\n)", "g");
        var drm_commentaire = (doc.commentaires)? (""+doc.commentaires).replace(regexp, " ") : null;

            for(certification_key in doc.declaration.certifications) {
                var certification = doc.declaration.certifications[certification_key];
                var certification_hash = "declaration/certifications/"+certification_key;
                var certification_code = certification_key;
                var certification_libelle = certification_key;
                var libelle = certification_libelle;
                var code = certification_code;
                for(genre_key in certification.genres) {
                    var genre = certification.genres[genre_key];
                    var genre_hash = certification_hash+"/genres/"+genre_key;
                if (certification_libelle) {
                    libelle = certification_libelle;
                }
                if (certification_code) {
                    code = certification_code;
                }
                var genre_code = (genre.code)? (genre.code).replace(code, '') : genre.code;
                var genre_libelle = (genre.libelle).replace(libelle, '');
                if (genre_code == key_default) {
                    genre_code = null;
                    genre_libelle = null;
                }
                    for(appellation_key in genre.appellations) {
                        var appellation = genre.appellations[appellation_key];
                        var appellation_hash = genre_hash+"/appellations/"+appellation_key;
                    if (genre.libelle && genre_code != key_default) {
                        libelle = genre.libelle;
                    }
                    if (genre.code && genre_code != key_default) {
                        code = genre.code;
                    }
		    var appellation_code = (appellation.code)? (appellation.code).replace(code, '') : appellation.code;
                    var appellation_libelle = (appellation.libelle).replace(libelle, '');
                    if (appellation_code == key_default) {
                        appellation_code = null;
                        appellation_libelle = null;
                    }
                        for(mention_key in appellation.mentions) {
                            var mention = appellation.mentions[mention_key];
                            var mention_hash = appellation_hash+"/mentions/"+mention_key;
                        if (appellation.libelle && appellation_code != key_default) {
                            libelle = appellation.libelle;
                        }
                        if (appellation.code && appellation_code != key_default) {
                            code = appellation.code;
                        }
                        var mention_libelle = (mention.libelle).replace(libelle, '');
						var mention_code = (mention.code)? (mention.code).replace(code, '') : mention.code;
                        if (mention_code == key_default) {
                            mention_code = null;
                            mention_libelle = null;
                        }
                            for(lieu_key in mention.lieux) {
                                var lieu = mention.lieux[lieu_key];
                                var lieu_hash = mention_hash+"/lieux/"+lieu_key;
                        if (mention.libelle && mention_code != key_default) {
                            libelle = mention.libelle;
                        }
                        if (mention.code && mention_code != key_default) {
                            code = mention.code;
                        }
						var lieu_code = (lieu.code)? (lieu.code).replace(code, '') : lieu.code;
                        var lieu_libelle = (lieu.libelle).replace(libelle, '');
                        if (lieu_code == key_default) {
                            lieu_code = null;
                            lieu_libelle = null;
                        }
                                for(couleur_key in lieu.couleurs) {
                                    var couleur = lieu.couleurs[couleur_key];
                                    var couleur_hash = lieu_hash+"/couleurs/"+couleur_key;
                        if (lieu.libelle && lieu_code != key_default) {
                            libelle = lieu.libelle;
                        }
                        if (lieu.code && lieu_code != key_default) {
                            code = lieu.code;
                        }
						var couleur_code = (couleur.code)? (couleur.code).replace(code, '') : couleur.code;
                        var couleur_libelle = (couleur.libelle) ? (couleur.libelle).replace(libelle, '') : null;
                        if (couleur_code == key_default) {
                            couleur_code = null;
                            couleur_libelle = null;
                        }
                                    for(cepage_key in couleur.cepages) {
                                        var cepage = couleur.cepages[cepage_key];
                                        var cepage_hash = couleur_hash+"/cepages/"+cepage_key;
                            if (couleur.libelle && couleur_code != key_default) {
                                libelle = couleur.libelle;
                            }
                            if (couleur.code && couleur_code != key_default) {
                                code = couleur.code;
                            }
							var cepage_code = (cepage.code)? (cepage.code).replace(code, '') : cepage.code;
                            var cepage_libelle = (cepage.libelle).replace(libelle, '');
                            var inao = (cepage.inao)? cepage.inao : null;
                            var libelle_fiscal = (cepage.libelle_fiscal)? cepage.libelle_fiscal : null;
                            if (cepage_code == key_default) {
                                cepage_code = null;
                                cepage_libelle = null;
                            }
                                        for(detail_key in cepage.details) {
                                            var detail = cepage.details[detail_key];
                                            var detail_hash =  cepage_hash+"/details/"+detail_key;
					    var volume_cvo = parseFloat(detail.sorties.vrac + detail.sorties.export + detail.sorties.factures + detail.sorties.crd + detail.sorties.crd_acquittes);
                                            if (isNaN(volume_cvo)) {
					    	volume_cvo = parseFloat(detail.sorties.vrac + detail.sorties.export + detail.sorties.factures + detail.sorties.crd);
					    }
					    if (isNaN(volume_cvo)) {
						volume_cvo = null;
					    }
						var volume_reintegre = parseFloat(detail.entrees.crd);
					    var montant_cvo = parseFloat(detail.cvo.taux) * volume_cvo;
						var montant_avoir = parseFloat(detail.cvo.taux) * volume_reintegre;
						if (drm_declarant_famille != 'producteur') {
							montant_avoir = 0
						}

                                            if (isNaN(montant_cvo) || parseFloat(detail.cvo.taux) === -1) {
                                                montant_cvo = null;
                                            }
											if (isNaN(montant_avoir) || parseFloat(detail.cvo.taux) === -1) {
                                                montant_avoir = null;
                                            }
                                            var libelles_label = '';
                                            var codes_label = '';
                                            for (label_key in detail.libelles_label) {
                                                if (libelles_label) {
                                                    libelles_label += '|';
                                                    codes_label += '|';
                                                }
                                                libelles_label += detail.libelles_label[label_key];
                                                codes_label += label_key;
                                            }
                                            var drm_observation = (detail.observations)? (""+detail.observations).replace(regexp, " ") : null;
                                            emit([detail.interpro, doc.valide.date_saisie, detail.has_vrac, doc._id, detail_hash, "PRODUIT"],
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
                                                     detail.libelle.trim(),
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
                                                     detail.sorties.autres,
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
                                                     volume_cvo,
                                                     montant_cvo,
                                                     drm_campagne,
                                                     drm_identifiant_drm_historique,
                                                     drm_identifiant_ivse,
                                                     null,
                                                     null,
                                                     drm_commentaire,
                                                     drm_referente,
                                                     drm_contrats_manquants,
                                                     drm_igp_manquants,
                                                     drm_observation,
						     detail.entrees.vci,
						     drm_declarant_famille,
						     drm_declarant_sousfamille,
						     inao,
						     libelle_fiscal,
						     detail.tav,
						     detail.entrees.manipulation,
						     detail.entrees.embouteillage,
						     detail.entrees.travail,
						     detail.entrees.distillation,
						     detail.entrees.excedent,
						     detail.sorties.crd_acquittes,
						     detail.sorties.mutages,
						     detail.sorties.vci,
						     detail.sorties.autres_interne,
						     detail.sorties.embouteillage,
						     detail.sorties.travail,
						     detail.sorties.distillation,
						     detail.acq_total_debut_mois,
						     detail.entrees.acq_achat,
						     detail.entrees.acq_autres,
						     detail.sorties.acq_crd,
						     detail.sorties.acq_replacement,
						     detail.sorties.acq_autres,
						     detail.acq_total,
							 montant_avoir

                                                     ]
                                            );

                                            var nb_vracs = Object.keys(detail.vrac).length;
                                            if (nb_vracs > 0) {
                                                for (numero_vrac in detail.vrac) {
                                                    var volume_vrac = detail.vrac[numero_vrac].volume;
                                                    emit([detail.interpro, doc.valide.date_saisie, detail.has_vrac, doc._id, detail_hash, "VRAC"],
                                                            [key_vrac,
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
                                                             detail.libelle.trim(),
                                                             null,
                                                             null,
                                                             null,
                                                             null,
                                                             null,
                                                             null,
                                                             null,
                                                             null,
                                                             null,
                                                             null,
                                                             null,
                                                             null,
                                                             null,
                                                             null,
                                                             null,
                                                             null,
                                                             null,
                                                             null,
                                                             null,
                                                             null,
                                                             null,
                                                             null,
                                                             null,
                                                             null,
                                                             null,
                                                             null,
                                                             null,
                                                             null,
                                                             null,
                                                             null,
                                                             null,
                                                             null,
                                                             null,
                                                             null,
                                                             null,
                                                             null,
                                                             drm_campagne,
                                                             drm_identifiant_drm_historique,
                                                             drm_identifiant_ivse,
                                                             numero_vrac,
                                                             volume_vrac,
                                                             drm_commentaire,
                                                             drm_referente,
                                                             drm_contrats_manquants,
                                                             drm_igp_manquants,
                                                             drm_observation,
							     null,
						     	     drm_declarant_famille,
						     	     drm_declarant_sousfamille,
								     inao,
								     libelle_fiscal,
								     detail.tav,
								     null,
								     null,
								     null,
								     null,
								     null,
								     null,
								     null,
								     null,
								     null,
								     null,
								     null,
								     null,
								     null,
								     null,
								     null,
								     null,
								     null,
								     null,
								     null,
									 null
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
}
