function(doc) {
    if(doc.type != "DRM") {
        return;
    }

    var getCampagne = function(campagne) {
    annee = campagne.substr(0,4);

        if (getMois(campagne) >= 08) {
            return annee + '-' (annee + 1);
    }

    return (annee - 1) + '-' + annee;
    };

    var getMois = function(campagne) {
        
        return campagne.substr(5, 2);
    }

    var parseDeclaration = function(declaration, functionNoeud, functionDetail) {
        for(certification_key in declaration.certifications) {
            var certification = declaration.certifications[certification_key];
            var certification_hash = "declaration/certifications/"+certification_key;
            functionNoeud('certification', [certification_key], certification_hash, certification, false);

            for(genre_key in certification.genres) {
                var genre = certification.genres[genre_key];
                var genre_hash = certification_hash+"/genres/"+genre_key;
                functionNoeud('genre', [certification_key, genre_key], genre_hash, certification, false);

                for(appellation_key in genre.appellations) {
                    var appellation = genre.appellations[appellation_key];
                    var appellation_hash = genre_hash+"/appellations/"+appellation_key;
                    functionNoeud('appellation', [certification_key, genre_key, appellation_key], appellation_hash, certification, false);

                    for(mention_key in appellation.mentions) {
                        var mention = appellation.mentions[mention_key];
                        var mention_hash = appellation_hash+"/mentions/"+appellation_key;
                        functionNoeud('mention', [certification_key, genre_key, appellation_key, mention_key], mention_hash, certification, false);

                        for(lieu_key in mention.lieux) {
                            var lieu = mention.lieux[lieu_key];
                            var lieu_hash = mention_hash+"/lieux/"+lieu_key;
                            functionNoeud('lieu', [certification_key, genre_key, appellation_key, mention_key, lieu_key], lieu_hash, certification, false);

                            for(couleur_key in lieu.couleurs) {
                                var couleur = lieu.couleurs[couleur_key];
                                var couleur_hash = lieu_hash+"/couleurs/"+couleur_key;
                                functionNoeud('couleur', [certification_key, genre_key, appellation_key, mention_key, lieu_key, couleur_key], couleur_hash, certification, false);

                                for(cepage_key in couleur.cepages) {
                                    var cepage = couleur.cepages[cepage_key];
                                    var cepage_hash = couleur_hash+"/cepages/"+cepage_key;
                                    functionNoeud('cepage', [certification_key, genre_key, appellation_key, mention_key, lieu_key, couleur_key, cepage_key], cepage_hash, certification, true);

                                    for(detail_key in cepage.details) {
                                        var detail = cepage.details[detail_key];
                                        var detail_hash =  cepage_hash+"/details/"+detail_key;

                                        functionDetail([certification_key, genre_key, appellation_key, mention_key, lieu_key, couleur_key, cepage_key, detail_key], detail_hash, detail);
                                    }
                                }
                            }
                        }
                    }
                }
            }
        } 
    }

    var functionNoeud = function(type, keys, hash, noeud, is_last_noeud) {
        if (is_last_noeud) {
            emit([getCampagne(doc.campagne), getMois(doc.campagne)].concat(keys), noeud.total);
        }
    }
    
    parseDeclaration(doc.declaration, functionNoeud, function() {});
}