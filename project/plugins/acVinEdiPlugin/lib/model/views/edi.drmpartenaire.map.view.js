function(doc) { 
	if (doc.type == "DRM" && doc.valide.date_saisie && doc.referente) {
		
		var getMvts = function(detail) {
			var mvts = new Array();
			var i = 0;
			for (entree in detail.entrees) {
				if (entree.indexOf('_details') != -1) { continue; }
				if (detail.entrees[entree]) {
					var acq = (entree.indexOf('acq_') == -1)? 'suspendu' : 'acquitte';
					mvts[i] = [acq, 'entrees', entree.replace('acq_', ''), (detail.entrees[entree]).toFixed(4), null, null];
					i++;
				}
			}
			for (sortie in detail.sorties) {
				if (sortie.indexOf('_details') != -1) { continue; }
				if (detail.sorties[sortie]) {
					var acq = (sortie.indexOf('acq_') == -1)? 'suspendu' : 'acquitte';
					mvts[i] = [acq, 'sorties', sortie.replace('acq_', ''), (detail.sorties[sortie]).toFixed(4), null, null];
					i++;
				}
			}
			if (detail.total_debut_mois) {
				mvts[i] = ['suspendu', 'stocks', 'total_debut_mois', (detail.total_debut_mois).toFixed(4), null, null];
				i++;
			}
			if (detail.total) {
				mvts[i] = ['suspendu', 'stocks', 'total', (detail.total).toFixed(4), null, null];
				i++;
			}
			if (detail.acq_total_debut_mois) {
				mvts[i] = ['acquitte', 'stocks', 'total_debut_mois', (detail.acq_total_debut_mois).toFixed(4), null, null];
				i++;
			}
			if (detail.acq_total) {
				mvts[i] = ['acquitte', 'stocks', 'total', (detail.acq_total).toFixed(4), null, null];
				i++;
			}
			if (detail.tav) {
				mvts[i] = ['suspendu', 'complement', 'tav', (detail.tav).toFixed(4), null, null];
				i++;
			}
			if (detail.premix) {
				mvts[i] = ['suspendu', 'complement', 'premix', 1, null, null];
				i++;
			}
			if (detail.observations) {
				mvts[i] = ['suspendu', 'complement', 'observations', detail.observations, null, null];
				i++;
			}
			for (vrac in detail.vrac) {
				mvts[i] = ['suspendu', 'complement', 'vrac', ((detail.vrac[vrac]).volume).toFixed(4), null, vrac];
				i++;
			}
            return mvts;
        }
		
		var declarant = doc.identifiant;
		if (doc.declarant.siret) {
			declarant += ' ('+doc.declarant.siret+')';
		} else if (doc.declarant.cvi) {
			declarant += ' ('+doc.declarant.cvi+')';
		}
		
		for(certification_key in doc.declaration.certifications) {
		    var certification_hash = "/declaration/certifications/"+certification_key;
            var certification = doc.declaration.certifications[certification_key];
		    for(genre_key in certification.genres) {
		        var genre_hash = certification_hash+"/genres/"+genre_key;
                var genre = certification.genres[genre_key];
		        for(appellation_key in genre.appellations) {
		            var appellation_hash = genre_hash+"/appellations/"+appellation_key;
                    var appellation = genre.appellations[appellation_key];
		            for(mention_key in appellation.mentions) {
		                var mention_hash = appellation_hash+"/mentions/"+mention_key;
                        var mention = appellation.mentions[mention_key];
		                for(lieu_key in mention.lieux) {
		                    var lieu_hash = mention_hash+"/lieux/"+lieu_key;
                            var lieu = mention.lieux[lieu_key];
		                    for(couleur_key in lieu.couleurs) {
		                        var couleur_hash = lieu_hash+"/couleurs/"+couleur_key;
                                var couleur = lieu.couleurs[couleur_key];
		                        for(cepage_key in couleur.cepages) {
		                            var cepage_hash = couleur_hash+"/cepages/"+cepage_key;
		                            var cepage = couleur.cepages[cepage_key];
		                            for(detail_key in cepage.details) {
		                                var detail_hash =  cepage_hash+"/details/"+detail_key;
		                                var detail = cepage.details[detail_key];
		                                var codesProduit = detail_hash.split('/');
		                                var mvts = getMvts(detail);
		                                var hasLabel =  (codesProduit[17] && codesProduit[17] != 'DEFAUT')? true : false;
		                                var produit = (hasLabel)? (detail.libelle).trim() + ' ' + codesProduit[17] : (detail.libelle).trim();
		                        		if (cepage.inao) {
		                        			produit += ' ('+cepage.inao+')';
		                        		} else if (cepage.libelle_fiscal) {
		                        			produit += ' ('+cepage.libelle_fiscal+')';
		                        		}
		                                for (mvt in mvts) {
			                                emit(
			                        				[detail.interpro, doc.valide.date_saisie], 
			                                    	[
			                                    	 	'CAVE',
			                                    	 	(doc.periode).replace('-', ''),
			                                    	 	declarant,
			                                    	 	doc.declarant.no_accises,
			                                    	 	(codesProduit[3] && codesProduit[3] != 'DEFAUT')? codesProduit[3] : null,
			                                    	 	(codesProduit[5] && codesProduit[5] != 'DEFAUT')? codesProduit[5] : null,
			                                    	 	(codesProduit[7] && codesProduit[7] != 'DEFAUT')? codesProduit[7] : null,
			                                    	 	(codesProduit[9] && codesProduit[9] != 'DEFAUT')? codesProduit[9] : null,
			                                            (codesProduit[11] && codesProduit[11] != 'DEFAUT')? codesProduit[11] : null,
			                                            (codesProduit[13] && codesProduit[13] != 'DEFAUT')? codesProduit[13] : null,
			                                            (codesProduit[15] && codesProduit[15] != 'DEFAUT')? codesProduit[15] : null,
			                                            (hasLabel)? codesProduit[17] : null,
			                                            produit,
			                                            mvts[mvt][0],
			                                            mvts[mvt][1],
			                                            mvts[mvt][2],
			                                            mvts[mvt][3],
			                                            mvts[mvt][4],
			                                            mvts[mvt][5]
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