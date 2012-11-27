function(doc) {
    var fctArrayMultiCopy = function (arrayToCopy) {
    	var newTab = new Array();
    	for(var i in arrayToCopy) {
			newTab[i] = new Array();
    		for (var j in arrayToCopy[i]) {
    			newTab[i][j] = arrayToCopy[i][j];
    		}
    	}
    	return newTab;
    }
    if (doc.type != "Configuration")
    return ;
    var doc = doc;
    var inter = new Array();
    var dep = new Array(new Array(""));
    var libelles = new Array();
    var codes = new Array();

    for (c in doc.declaration.certifications) {
    	// JB
    	var interprosCertification = new Array();
    	for (i in doc.declaration.certifications[c].interpro)
    	{
    		interprosCertification[i] = new Array();
    		interprosCertification[i]["cvo"] = new Array();
    		interprosCertification[i]["cvo"] = doc.declaration.certifications[c].interpro[i].droits.cvo[doc.declaration.certifications[c].interpro[i].droits.cvo.length - 1];
    	}
    	// /JB
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
        	// JB
    		var interprosGenre = fctArrayMultiCopy(interprosCertification);
    		if (doc.declaration.certifications[c].genres[g].hasOwnProperty("interpro"))
    		{
    			nb = 0;
    			for (item in doc.declaration.certifications[c].genres[g].interpro) {
    				nb = nb + 1;
    			}
    			if (nb > 0) {
	    			interprosGenre = new Array();
		        	for (i in doc.declaration.certifications[c].genres[g].interpro)
		        	{
		        		interprosGenre[i] = new Array();
		        		interprosGenre[i]["cvo"] = new Array();
		        		if (interprosCertification.hasOwnProperty(i))
		        		{
		        			interprosGenre[i]["cvo"] = interprosCertification[i]["cvo"];
		        		}
		        		if (doc.declaration.certifications[c].genres[g].interpro[i].hasOwnProperty("droits"))
		        		{
		        			if (doc.declaration.certifications[c].genres[g].interpro[i].droits.hasOwnProperty("cvo") && doc.declaration.certifications[c].genres[g].interpro[i].droits.cvo.length > 0) 
		        			{
		        				interprosGenre[i]["cvo"] = doc.declaration.certifications[c].genres[g].interpro[i].droits.cvo[doc.declaration.certifications[c].genres[g].interpro[i].droits.cvo.length - 1];
		        			}
		        		}
		        	}
    			}
    		}
    		// /JB
            var interpros = new Array();
            for(interpro_key in doc.declaration.certifications[c].genres[g].interpro) {
                interpros.push(interpro_key);
            }
            inter.unshift(interpros);
            dep.unshift(doc.declaration.certifications[c].genres[g].departements);
            libelles.push(doc.declaration.certifications[c].genres[g].libelle);
            codes.push(doc.declaration.certifications[c].genres[g].code);
            for (a in doc.declaration.certifications[c].genres[g].appellations) {
            	// JB
        		var interprosAppellation = fctArrayMultiCopy(interprosGenre);
        		if (doc.declaration.certifications[c].genres[g].appellations[a].hasOwnProperty("interpro"))
        		{
        			nb = 0;
        			for (item in doc.declaration.certifications[c].genres[g].appellations[a].interpro) {
        				nb = nb + 1;
        			}
        			if (nb > 0) {
	        			interprosAppellation = new Array();
		            	for (i in doc.declaration.certifications[c].genres[g].appellations[a].interpro)
		            	{
		            		interprosAppellation[i] = new Array();
		            		interprosAppellation[i]["cvo"] = new Array();
		            		if (interprosGenre.hasOwnProperty(i))
		            		{
		            			interprosAppellation[i]["cvo"] = interprosGenre[i]["cvo"];
		            		}
		            		if (doc.declaration.certifications[c].genres[g].appellations[a].interpro[i].hasOwnProperty("droits"))
		            		{
		            			if (doc.declaration.certifications[c].genres[g].appellations[a].interpro[i].droits.hasOwnProperty("cvo") && doc.declaration.certifications[c].genres[g].appellations[a].interpro[i].droits.cvo.length > 0) 
		            			{
		            				interprosAppellation[i]["cvo"] = doc.declaration.certifications[c].genres[g].appellations[a].interpro[i].droits.cvo[doc.declaration.certifications[c].genres[g].appellations[a].interpro[i].droits.cvo.length - 1];
		            			}
		            		}
		            	}
        			}
        		}
            	// /JB
                var interpros = new Array();
                for(interpro_key in doc.declaration.certifications[c].genres[g].appellations[a].interpro) {
                    interpros.push(interpro_key);
                }
                inter.unshift(interpros);
                dep.unshift(doc.declaration.certifications[c].genres[g].appellations[a].departements);
                libelles.push(doc.declaration.certifications[c].genres[g].appellations[a].libelle);
                codes.push(doc.declaration.certifications[c].genres[g].appellations[a].code);
                for (m in doc.declaration.certifications[c].genres[g].appellations[a].mentions) {
                	// JB
        			var interprosMention = fctArrayMultiCopy(interprosAppellation);
        			if (doc.declaration.certifications[c].genres[g].appellations[a].mentions[m].hasOwnProperty("interpro"))
        			{
        				nb = 0;
        				for (item in doc.declaration.certifications[c].genres[g].appellations[a].mentions[m].interpro) {
        					nb = nb + 1;
        				}
        				if (nb > 0)
        				{
        		    		interprosMention = new Array();
        		        	for (i in doc.declaration.certifications[c].genres[g].appellations[a].mentions[m].interpro)
        		        	{
        		        		interprosMention[i] = new Array();
        		        		interprosMention[i]["cvo"] = new Array();
        		        		if (interprosAppellation.hasOwnProperty(i))
        		        		{
        		        			interprosMention[i]["cvo"] = interprosAppellation[i]["cvo"];
        		        		}
        		        		if (doc.declaration.certifications[c].genres[g].appellations[a].mentions[m].interpro[i].hasOwnProperty("droits"))
        		        		{
        		        			if (doc.declaration.certifications[c].genres[g].appellations[a].mentions[m].interpro[i].droits.hasOwnProperty("cvo") && doc.declaration.certifications[c].genres[g].appellations[a].mentions[m].interpro[i].droits.cvo.length > 0) 
        		        			{
        		        				interprosMention[i]["cvo"] = doc.declaration.certifications[c].genres[g].appellations[a].mentions[m].interpro[i].droits.cvo[doc.declaration.certifications[c].genres[g].appellations[a].mentions[m].interpro[i].droits.cvo.length - 1];
        		        			}
        		        		}
        		        	}
        				}
        			}
                	// /JB
                	dep.unshift(doc.declaration.certifications[c].genres[g].appellations[a].mentions[m].departements);
                    libelles.push(doc.declaration.certifications[c].genres[g].appellations[a].mentions[m].libelle);
                    codes.push(doc.declaration.certifications[c].genres[g].appellations[a].mentions[m].code);
                	for(l in doc.declaration.certifications[c].genres[g].appellations[a].mentions[m].lieux) {
                		// JB
	            		var interprosLieu = fctArrayMultiCopy(interprosMention);
	            		if (doc.declaration.certifications[c].genres[g].appellations[a].mentions[m].lieux[l].hasOwnProperty("interpro"))
	            		{
	            			nb = 0;
	            			for (item in doc.declaration.certifications[c].genres[g].appellations[a].mentions[m].lieux[l].interpro) {
	            				nb = nb + 1;
	            			}
	            			if (nb > 0)
	            			{
		                		interprosLieu = new Array();
			                	for (i in doc.declaration.certifications[c].genres[g].appellations[a].mentions[m].lieux[l].interpro)
			                	{
			                		interprosLieu[i] = new Array();
			                		interprosLieu[i]["cvo"] = new Array();
			                		if (interprosMention.hasOwnProperty(i))
			                		{
			                			interprosLieu[i]["cvo"] = interprosMention[i]["cvo"];
			                		}
			                		if (doc.declaration.certifications[c].genres[g].appellations[a].mentions[m].lieux[l].interpro[i].hasOwnProperty("droits"))
			                		{
				            			if (doc.declaration.certifications[c].genres[g].appellations[a].mentions[m].lieux[l].interpro[i].droits.hasOwnProperty("cvo") && doc.declaration.certifications[c].genres[g].appellations[a].mentions[m].lieux[l].interpro[i].droits.cvo.length > 0) 
				            			{
				            				interprosLieu[i]["cvo"] = doc.declaration.certifications[c].genres[g].appellations[a].mentions[m].lieux[l].interpro[i].droits.cvo[doc.declaration.certifications[c].genres[g].appellations[a].mentions[m].lieux[l].interpro[i].droits.cvo.length - 1];
				            			}
			                		}
			                	}
	            			}
	            		}
                		// /JB
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
	                                            emit(["lieux", dep[array_dep_key][d], "", hash, codes.join('')], {libelles: libes, codes: codesv});
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
	                                                    emit(["produits", dep[array_dep_key][d], hash_lieu, hash, codes.join('')], {libelles: libes, codes: codesv, cvo: interprosLieu[inter[i]]["cvo"]});
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