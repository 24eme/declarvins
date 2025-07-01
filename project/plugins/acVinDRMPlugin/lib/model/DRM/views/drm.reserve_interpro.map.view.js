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

        var key_default = 'DEFAUT';
        var drm_id = doc._id;
        var drm_campagne = doc.campagne;
        var drm_explosed_periode = (doc.periode).split('-');
        var drm_identifiant = doc.identifiant;
        var drm_declarant = doc.declarant.raison_sociale;
        var drm_declarant_famille = doc.declarant.famille;
        var drm_declarant_sousfamille = doc.declarant.sous_famille;
        var drm_annee = getAnneeByDRM(drm_explosed_periode);
        var drm_mois = getMoisByDRM(drm_explosed_periode);
        var drm_version = groupByLastVersion(doc.version);
        var drm_referente = (doc.referente)? doc.referente : 0;
        var reserve_interpro = [];

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
                                        var libelles_label = '';
                                        var codes_label = '';
                                        var interpro = detail.interpro;
                                        for (label_key in detail.libelles_label) {
                                            if (libelles_label) {
                                                libelles_label += '|';
                                                codes_label += '|';
                                            }
                                            libelles_label += detail.libelles_label[label_key];
                                            codes_label += label_key;
                                        }
				    	reserve_interpro = [];
                                        if (detail.hasOwnProperty('reserve_interpro_details')) {
		                            for (millesime in detail.reserve_interpro_details) {
		                                reserve_interpro[millesime] = detail.reserve_interpro_details[millesime];
		                            }
                                        }
                                        if (detail.hasOwnProperty('reserve_interpro')) {
                                            reserve_interpro['TOTAL'] = detail.reserve_interpro;
                                        }
	                            	if (detail.hasOwnProperty('reserve_interpro_capacite_commercialisation')) {
	                                    reserve_interpro['CAPACITE_COMMERCIALISATION'] = detail.reserve_interpro_capacite_commercialisation;
	                            	}
	                            	if (detail.hasOwnProperty('reserve_interpro_suivi_sorties_chais')) {
	                                    reserve_interpro['CUMUL_SORTIES_CHAIS'] = detail.reserve_interpro_suivi_sorties_chais;
	                            	}
                                        if (reserve_interpro) {
				            for(millesime in reserve_interpro) {
		                                emit([detail.interpro, drm_identifiant, drm_annee+drm_mois, millesime], [
		                                             drm_identifiant,
		                                             drm_declarant,
							     drm_declarant_famille,
							     drm_declarant_sousfamille,
		                                             drm_campagne,
		                                             drm_annee,
		                                             drm_mois,
		                                             drm_version,
		                                             drm_referente,
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
		                                             detail.libelle.trim(),
							     inao,
							     libelle_fiscal,
		                                             libelles_label,
		                                             codes_label,
		                                             millesime,
		                                             reserve_interpro[millesime]
		                                ]);
		                            }
		                        }
                                    }

				    reserve_interpro = [];
                                    if (cepage.hasOwnProperty('reserve_interpro_details')) {
	                                for (millesime in cepage.reserve_interpro_details) {
	                                    reserve_interpro[millesime] = cepage.reserve_interpro_details[millesime];
	                                }
                                    }
                                    if (cepage.hasOwnProperty('reserve_interpro')) {
                                        reserve_interpro['TOTAL'] = cepage.reserve_interpro;
                                    }
                                    if (cepage.hasOwnProperty('reserve_interpro_capacite_commercialisation')) {
                                        reserve_interpro['CAPACITE_COMMERCIALISATION'] = cepage.reserve_interpro_capacite_commercialisation;
                                    }
                                    if (cepage.hasOwnProperty('reserve_interpro_suivi_sorties_chais')) {
                                        reserve_interpro['CUMUL_SORTIES_CHAIS'] = cepage.reserve_interpro_suivi_sorties_chais;
                                    }
                                    if (reserve_interpro) {
			                for(millesime in reserve_interpro) {
	                                    emit([interpro, drm_identifiant, drm_annee+drm_mois, millesime], [
	                                             drm_identifiant,
	                                             drm_declarant,
						     drm_declarant_famille,
						     drm_declarant_sousfamille,
	                                             drm_campagne,
	                                             drm_annee,
	                                             drm_mois,
	                                             drm_version,
	                                             drm_referente,
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
	                                             cepage.libelle.trim(),
						     inao,
						     libelle_fiscal,
	                                             null,
	                                             null,
	                                             millesime,
	                                             reserve_interpro[millesime]
	                                    ]);
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
