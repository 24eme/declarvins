function(doc) {
    if (doc.type != "Configuration")
    return ;
    var inter = new Array();
    var dep = new Array(new Array(""));
    var libelles = new Array();
    var codes = new Array();

    for (c in doc.declaration.certifications) {
        var interpros = new Array();
        for(interpro_key in doc.declaration.certifications[c].interpro) {
            interpros.push(interpro_key);
            for(label_index in doc.declaration.certifications[c].interpro[interpro_key].labels) {
                label_key = doc.declaration.certifications[c].interpro[interpro_key].labels[label_index];
                emit(["labels", interpro_key, c, "", "", label_key], doc.labels[label_key], "");
            }
        }
        inter.unshift(interpros);
        dep.unshift(doc.declaration.certifications[c].departements);
        libelles.push(doc.declaration.certifications[c].libelle);
        codes.push(doc.declaration.certifications[c].code);
        for (g in doc.declaration.certifications[c].genres) {
            var interpros = new Array();
            for(interpro_key in doc.declaration.certifications[c].genres[g].interpro) {
                interpros.push(interpro_key);
            }
            inter.unshift(interpros);
            dep.unshift(doc.declaration.certifications[c].genres[g].departements);
            libelles.push(doc.declaration.certifications[c].genres[g].libelle);
            codes.push(doc.declaration.certifications[c].genres[g].code);
            for (a in doc.declaration.certifications[c].genres[g].appellations) {
                var interpros = new Array();
                for(interpro_key in doc.declaration.certifications[c].genres[g].appellations[a].interpro) {
                    interpros.push(interpro_key);
                }
                inter.unshift(interpros);
                dep.unshift(doc.declaration.certifications[c].genres[g].appellations[a].departements);
                libelles.push(doc.declaration.certifications[c].genres[g].appellations[a].libelle);
                codes.push(doc.declaration.certifications[c].genres[g].appellations[a].code);
                for (m in doc.declaration.certifications[c].genres[g].appellations[a].mentions) {
                	dep.unshift(doc.declaration.certifications[c].genres[g].appellations[a].mentions[m].departements);
                    libelles.push(doc.declaration.certifications[c].genres[g].appellations[a].mentions[m].libelle);
                    codes.push(doc.declaration.certifications[c].genres[g].appellations[a].mentions[m].code);
                	for(l in doc.declaration.certifications[c].genres[g].appellations[a].mentions[m].lieux) {
	                    dep.unshift(doc.declaration.certifications[c].genres[g].appellations[a].mentions[m].lieux[l].departements);
	                    libelles.push(doc.declaration.certifications[c].genres[g].appellations[a].mentions[m].lieux[l].libelle);
	                    codes.push(doc.declaration.certifications[c].genres[g].appellations[a].mentions[m].lieux[l].code);
	                    var libes = libelles.slice();
	                    var codesv = codes.slice();
	                    var hash = "declaration/certifications/"+c+"/genres/"+g+"/appellations/"+a+"/mentions/"+m+"/lieux/"+l;
	                    for(i in inter) {
	                        if (inter[i].length > 0) {
	                            for(array_intepro_key in inter) {
	                                for(array_dep_key in dep) {
	                                    if (dep[array_dep_key].length > 0) {
	                                        for(d in dep[array_dep_key]) {
	                                            emit(["lieux", inter[i][array_intepro_key], c, dep[array_dep_key][d], "", hash, codes.join('')], {libelles: libes, codes: codesv});
	                                        }
	                                        break;
	                                    }
	                                }
	                                break;
	                            }
	                            break;
	                        }
	                    }
	                    for(co in doc.declaration.certifications[c].genres[g].appellations[a].mentions[m].lieux[l].couleurs) {
	                        libelles.push(doc.declaration.certifications[c].genres[g].appellations[a].mentions[m].lieux[l].couleurs[co].libelle);
	                        codes.push(doc.declaration.certifications[c].genres[g].appellations[a].mentions[m].lieux[l].couleurs[co].code);
	                        for(ce in doc.declaration.certifications[c].genres[g].appellations[a].mentions[m].lieux[l].couleurs[co].cepages) {
	                            libelles.push(doc.declaration.certifications[c].genres[g].appellations[a].mentions[m].lieux[l].couleurs[co].cepages[ce].libelle);
	                            codes.push(doc.declaration.certifications[c].genres[g].appellations[a].mentions[m].lieux[l].couleurs[co].cepages[ce].code);        
	                            var libes = libelles.slice();
	                            var codesv = codes.slice();
	                            var hash = "declaration/certifications/"+c+"/genres/"+g+"/appellations/"+a+"/mentions/"+m+"/lieux/"+l+"/couleurs/"+co+"/cepages/"+ce;
	                            var hash_lieu = "declaration/certifications/"+c+"/genres/"+g+"/appellations/"+a+"/mentions/"+m+"/lieux/"+l;
	                            for(i in inter) {
	                                if (inter[i].length > 0) {
	                                    for(array_intepro_key in inter) {
	                                        for(array_dep_key in dep) {
	                                            if (dep[array_dep_key].length > 0) {
	                                                for(d in dep[array_dep_key]) {
	                                                    emit(["produits", inter[i][array_intepro_key], c, dep[array_dep_key][d], hash_lieu, hash, codes.join('')], {libelles: libes, codes: codesv});
	                                                }
	                                                break;
	                                            }
	                                        }
	                                        break;
	                                    }
	                                    break;
	                                }
	                            }
	                            libelles.splice(libelles.length-1, 1);
	                            codes.splice(codes.length-1, 1);
	                        }
	                        libelles.splice(libelles.length-1, 1);
	                        codes.splice(codes.length-1, 1);
	                    }
	                    dep.splice(0, 1);
	                    libelles.splice(libelles.length-1, 1);
	                    codes.splice(codes.length-1, 1);
	                }
                    dep.splice(0, 1);
                    libelles.splice(libelles.length-1, 1);
                    codes.splice(codes.length-1, 1);
            	}
                inter.splice(0,1);
                dep.splice(0, 1);
                libelles.splice(libelles.length-1, 1);
                codes.splice(codes.length-1, 1);
            }
            inter.splice(0, 1);
            dep.splice(0, 1);
            libelles.splice(libelles.length-1, 1);
            codes.splice(codes.length-1, 1);
        }
        inter.splice(0, 1);
        dep.splice(0, 1);
        libelles.splice(libelles.length-1, 1);
        codes.splice(codes.length-1, 1);
    }
}
