function(doc) 
{
    if (doc.type != "Configuration")
    	return ;
    var nb = 0;
    var noeudDepartements = '';
    var noeudLabels = '';
    var noeudDouane = '';
    var noeudCvo = '';
    var noeudRepli = '';
    var noeudDeclassement = '';
    for (certification in doc.declaration.certifications) 
    {
    	var certificationLibelle = doc.declaration.certifications[certification].libelle;
    	var departementsCertification = doc.declaration.certifications[certification].departements;
    	var detailCertification = doc.declaration.certifications[certification].detail;
    	var interprosCertification = new Array();
        noeudDepartements = 'Catégorie';
        noeudLabels = 'Catégorie';
        noeudDouane = 'Catégorie';
        noeudCvo = 'Catégorie';
        noeudRepli = 'Catégorie';
        noeudDeclassement = 'Catégorie';
    	for (i in doc.declaration.certifications[certification].interpro)
    	{
    		interprosCertification[i] = new Array();
    		interprosCertification[i]["label"] = doc.declaration.certifications[certification].interpro[i].labels;
    		interprosCertification[i]["douane"] = doc.declaration.certifications[certification].interpro[i].droits.douane[doc.declaration.certifications[certification].interpro[i].droits.douane.length - 1];
    		interprosCertification[i]["cvo"] = doc.declaration.certifications[certification].interpro[i].droits.cvo[doc.declaration.certifications[certification].interpro[i].droits.cvo.length - 1];
    	}
    	
    	for (genre in doc.declaration.certifications[certification].genres)
    	{
    		var genreLibelle = doc.declaration.certifications[certification].genres[genre].libelle;
    		var departementsGenre = doc.declaration.certifications[certification].genres[genre].departements;
    		var detailGenre = detailCertification;
    		var interprosGenre = interprosCertification;
    		if (doc.declaration.certifications[certification].genres[genre].hasOwnProperty("interpro"))
    		{
    			nb = 0;
    			for (item in doc.declaration.certifications[certification].genres[genre].interpro) {
    				nb = nb + 1;
    			}
    			if (nb > 0) {
	    			interprosGenre = new Array();
		        	for (i in doc.declaration.certifications[certification].genres[genre].interpro)
		        	{
		        		interprosGenre[i] = new Array();
		        		interprosGenre[i]["label"] = new Array();
		        		interprosGenre[i]["douane"] = new Array();
		        		interprosGenre[i]["cvo"] = new Array();
		        		if (interprosCertification.hasOwnProperty(i))
		        		{
		        			interprosGenre[i]["label"] = interprosCertification[i]["label"];
		        			interprosGenre[i]["douane"] = interprosCertification[i]["douane"];
		        			interprosGenre[i]["cvo"] = interprosCertification[i]["cvo"];
		        		}
		        		/* LABELS */
		        		var labels = fctGetLabels(doc.declaration.certifications[certification].genres[genre], i);
		        		if (labels) {
		        			interprosGenre[i]["label"] = labels;
		        	        noeudLabels = 'Genre';
		        		}
		        		var cvo = fctGetCvo(doc.declaration.certifications[certification].genres[genre], i);
		        		var douane = fctGetDouane(doc.declaration.certifications[certification].genres[genre], i);
		        		if (cvo) {
		        			interprosGenre[i]["cvo"] = cvo;
		        			noeudCvo = 'Genre';
		        		} else {
		        			
		        		}
		        		if (douane) {
		        			interprosGenre[i]["douane"] = douane;
		        			noeudDouane = 'Genre';
		        		} else {
		        			
		        		}
		        	}
    			}
    		}
    		if (departementsGenre.length == 0)
    		{
    			departementsGenre = departementsCertification;
    		}
    		else
    		{
    			noeudDepartements = 'Genre';
    		}
    		if (doc.declaration.certifications[certification].genres[genre].hasOwnProperty('detail'))
    		{
    			if (doc.declaration.certifications[certification].genres[genre].detail.entrees.repli.readable && doc.declaration.certifications[certification].genres[genre].detail.entrees.repli.declassement && doc.declaration.certifications[certification].genres[genre].detail.sorties.repli.readable && doc.declaration.certifications[certification].genres[genre].detail.sorties.declassement.readable) {
	    			detailGenre = doc.declaration.certifications[certification].genres[genre].detail;
	    	        noeudRepli = 'Genre';
	    	        noeudDeclassement = 'Genre';
    			}
    		}
    		for (appellation in doc.declaration.certifications[certification].genres[genre].appellations) 
    		{
    			var appellationLibelle = doc.declaration.certifications[certification].genres[genre].appellations[appellation].libelle;
        		var departementsAppellation = doc.declaration.certifications[certification].genres[genre].appellations[appellation].departements;
        		var detailAppellation = detailGenre;
        		var interprosAppellation = interprosGenre;
        		if (doc.declaration.certifications[certification].genres[genre].appellations[appellation].hasOwnProperty("interpro"))
        		{
        			nb = 0;
        			for (item in doc.declaration.certifications[certification].genres[genre].appellations[appellation].interpro) {
        				nb = nb + 1;
        			}
        			if (nb > 0) {
	        			interprosAppellation = new Array();
		            	for (i in doc.declaration.certifications[certification].genres[genre].appellations[appellation].interpro)
		            	{
		            		interprosAppellation[i] = new Array();
		            		interprosAppellation[i]["label"] = new Array();
		            		interprosAppellation[i]["douane"] = new Array();
		            		interprosAppellation[i]["cvo"] = new Array();
		            		if (interprosGenre.hasOwnProperty(i))
		            		{
		            			interprosAppellation[i]["label"] = interprosGenre[i]["label"];
		            			interprosAppellation[i]["douane"] = interprosGenre[i]["douane"];
		            			interprosAppellation[i]["cvo"] = interprosGenre[i]["cvo"];
		            		}
			        		var labels = fctGetLabels(doc.declaration.certifications[certification].genres[genre].appellations[appellation], i);
			        		if (labels) {
			        			interprosGenre[i]["label"] = labels;
			        	        noeudLabels = 'Dénomination';
			        		}
			        		var cvo = fctGetCvo(doc.declaration.certifications[certification].genres[genre].appellations[appellation], i);
			        		var douane = fctGetDouane(doc.declaration.certifications[certification].genres[genre].appellations[appellation], i);
			        		if (cvo) {
			        			interprosGenre[i]["cvo"] = cvo;
			        			noeudCvo = 'Dénomination';
			        		} else {
			        			
			        		}
			        		if (douane) {
			        			interprosGenre[i]["douane"] = douane;
			        			noeudDouane = 'Dénomination';
			        		} else {
			        		
			        		}
		            	}
        			}
        		}
        		if (departementsAppellation.length == 0)
        		{
        			departementsAppellation = departementsGenre;
        		}
        		else
        		{
        			noeudDepartements = 'Dénomination';
        		}
        		if (doc.declaration.certifications[certification].genres[genre].appellations[appellation].hasOwnProperty('detail'))
        		{
        			if (doc.declaration.certifications[certification].genres[genre].appellations[appellation].detail.entrees.repli.readable && doc.declaration.certifications[certification].genres[genre].appellations[appellation].detail.entrees.repli.declassement && doc.declaration.certifications[certification].genres[genre].appellations[appellation].detail.sorties.repli.readable && doc.declaration.certifications[certification].genres[genre].appellations[appellation].detail.sorties.declassement.readable) {
	        			detailAppellation = doc.declaration.certifications[certification].genres[genre].appellations[appellation].detail;
	        	        noeudRepli = 'Dénomination';
	        	        noeudDeclassement = 'Dénomination';
        			}
        		}
        		for(mention in doc.declaration.certifications[certification].genres[genre].appellations[appellation].mentions) 
        		{
        			var mentionLibelle = doc.declaration.certifications[certification].genres[genre].appellations[appellation].mentions[mention].libelle;
        			var departementsMention = doc.declaration.certifications[certification].genres[genre].appellations[appellation].mentions[mention].departements;
        			var detailMention = detailAppellation;
        			var interprosMention = interprosAppellation;
        			if (doc.declaration.certifications[certification].genres[genre].appellations[appellation].mentions[mention].hasOwnProperty("interpro"))
        			{
        				nb = 0;
        				for (item in doc.declaration.certifications[certification].genres[genre].appellations[appellation].mentions[mention].interpro) {
        					nb = nb + 1;
        				}
        				if (nb > 0)
        				{
        		    		interprosMention = new Array();
        		        	for (i in doc.declaration.certifications[certification].genres[genre].appellations[appellation].mentions[mention].interpro)
        		        	{
        		        		interprosMention[i] = new Array();
        		        		interprosMention[i]["label"] = new Array();
        		        		interprosMention[i]["douane"] = new Array();
        		        		interprosMention[i]["cvo"] = new Array();
        		        		if (interprosAppellation.hasOwnProperty(i))
        		        		{
        		        			interprosMention[i]["label"] = interprosAppellation[i]["label"];
        		        			interprosMention[i]["douane"] = interprosAppellation[i]["douane"];
        		        			interprosMention[i]["cvo"] = interprosAppellation[i]["cvo"];
        		        		}

    			        		/* LABELS */
    			        		/*var labels = fctGetLabels(doc.declaration.certifications[certification].genres[genre].appellations[appellation].mentions[mention]);
    			        		if (labels) {
    			        			interprosGenre[i]["label"] = labels;
    			        	        noeudLabels = 'Mention';
    			        		}
    			        		var cvo = fctGetCvo(doc.declaration.certifications[certification].genres[genre].appellations[appellation].mentions[mention], i);
    			        		var douane = fctGetDouane(doc.declaration.certifications[certification].genres[genre].appellations[appellation].mentions[mention], i);
    			        		if (cvo) {
    			        			interprosGenre[i]["cvo"] = cvo;
    			        			noeudCvo = 'Mention';
    			        		} else {
    			        			
    			        		}
    			        		if (douane) {
    			        			interprosGenre[i]["douane"] = douane;
    			        			noeudDouane = 'Mention';
    			        		} else {
    			        			
    			        		}*/
        		        	}
        				}
        			}
        			if (departementsMention.length == 0)
        			{
        				departementsMention = departementsAppellation;
        			}
        			else
        			{
            	        noeudDepartements = 'Mention';
        			}

            		/* DETAIL */
            		/*var detail = fctGetDetail(doc.declaration.certifications[certification].genres[genre].appellations[appellation].mentions[mention]);
            		if (detail) {
            			detailGenre = detail;
            	        noeudRepli = 'Mention';
            	        noeudDeclassement = 'Mention';
            		}*/
	    			for(lieu in doc.declaration.certifications[certification].genres[genre].appellations[appellation].mentions[mention].lieux) 
	    			{
	    				var lieuLibelle = doc.declaration.certifications[certification].genres[genre].appellations[appellation].mentions[mention].lieux[lieu].libelle;
	            		var departementsLieu = doc.declaration.certifications[certification].genres[genre].appellations[appellation].mentions[mention].lieux[lieu].departements;
	            		var detailLieu = detailMention;
	            		var interprosLieu = interprosMention;
	            		if (doc.declaration.certifications[certification].genres[genre].appellations[appellation].mentions[mention].lieux[lieu].hasOwnProperty("interpro"))
	            		{
	            			nb = 0;
	            			for (item in doc.declaration.certifications[certification].genres[genre].appellations[appellation].mentions[mention].lieux[lieu].interpro) {
	            				nb = nb + 1;
	            			}
	            			if (nb > 0)
	            			{
		                		interprosLieu = new Array();
			                	for (i in doc.declaration.certifications[certification].genres[genre].appellations[appellation].mentions[mention].lieux[lieu].interpro)
			                	{
			                		interprosLieu[i] = new Array();
			                		interprosLieu[i]["label"] = new Array();
			                		interprosLieu[i]["douane"] = new Array();
			                		interprosLieu[i]["cvo"] = new Array();
			                		if (interprosMention.hasOwnProperty(i))
			                		{
			                			interprosLieu[i]["label"] = interprosMention[i]["label"];
			                			interprosLieu[i]["douane"] = interprosMention[i]["douane"];
			                			interprosLieu[i]["cvo"] = interprosMention[i]["cvo"];
			                		}

	    			        		/* LABELS */
	    			        		/*var labels = fctGetLabels(doc.declaration.certifications[certification].genres[genre].appellations[appellation].mentions[mention].lieux[lieu]);
	    			        		if (labels) {
	    			        			interprosGenre[i]["label"] = labels;
	    			        	        noeudLabels = 'Lieu';
	    			        		}
	    			        		var cvo = fctGetCvo(doc.declaration.certifications[certification].genres[genre].appellations[appellation].mentions[mention].lieux[lieu], i);
	    			        		var douane = fctGetDouane(doc.declaration.certifications[certification].genres[genre].appellations[appellation].mentions[mention].lieux[lieu], i);
	    			        		if (cvo) {
	    			        			interprosGenre[i]["cvo"] = cvo;
	    			        			noeudCvo = 'Lieu';
	    			        		} else {
	    			        			
	    			        		}
	    			        		if (douane) {
	    			        			interprosGenre[i]["douane"] = douane;
	    			        			noeudDouane = 'Lieu';
	    			        		} else {
	    			        			
	    			        		}*/
			                	}
	            			}
	            		}
	            		if (departementsLieu.length == 0)
	            		{
	            			departementsLieu = departementsMention;
	            		} 
	            		else 
	            		{
	            			noeudDepartements = 'Lieu';
	            		}

	            		/* DETAIL */
	            		/*var detail = fctGetDetail(doc.declaration.certifications[certification].genres[genre].appellations[appellation].mentions[mention].lieux[lieu]);
	            		if (detail) {
	            			detailGenre = detail;
	            	        noeudRepli = 'Lieu';
	            	        noeudDeclassement = 'Lieu';
	            		}*/
	    				
	    				for(couleur in doc.declaration.certifications[certification].genres[genre].appellations[appellation].mentions[mention].lieux[lieu].couleurs) 
	    				{
	    					var couleurLibelle = doc.declaration.certifications[certification].genres[genre].appellations[appellation].mentions[mention].lieux[lieu].couleurs[couleur].libelle;
		            		var interprosCouleur = interprosLieu;
		            		if (doc.declaration.certifications[certification].genres[genre].appellations[appellation].mentions[mention].lieux[lieu].couleurs[couleur].hasOwnProperty("interpro"))
		            		{
		            			nb = 0;
		            			for (item in doc.declaration.certifications[certification].genres[genre].appellations[appellation].mentions[mention].lieux[lieu].couleurs[couleur].interpro) {
		            				nb = nb + 1;
		            			}
		            			if (nb > 0)
		            			{
		            				interprosCouleur = new Array();
				                	for (i in doc.declaration.certifications[certification].genres[genre].appellations[appellation].mentions[mention].lieux[lieu].couleurs[couleur].interpro)
				                	{
				                		interprosCouleur[i] = new Array();
				                		interprosCouleur[i]["label"] = new Array();
				                		interprosCouleur[i]["douane"] = new Array();
				                		interprosCouleur[i]["cvo"] = new Array();
				                		if (interprosLieu.hasOwnProperty(i))
				                		{
				                			interprosCouleur[i]["label"] = interprosLieu[i]["label"];
				                			interprosCouleur[i]["douane"] = interprosLieu[i]["douane"];
				                			interprosCouleur[i]["cvo"] = interprosLieu[i]["cvo"];
				                		}

		    			        		/* DROITS */
		    			        		/*var cvo = fctGetCvo(doc.declaration.certifications[certification].genres[genre].appellations[appellation].mentions[mention].lieux[lieu].couleurs[couleur], i);
		    			        		var douane = fctGetDouane(doc.declaration.certifications[certification].genres[genre].appellations[appellation].mentions[mention].lieux[lieu].couleurs[couleur], i);
		    			        		if (cvo) {
		    			        			interprosGenre[i]["cvo"] = cvo;
		    			        			noeudCvo = 'Couleur';
		    			        		} else {
		    			        			
		    			        		}
		    			        		if (douane) {
		    			        			interprosGenre[i]["douane"] = douane;
		    			        			noeudDouane = 'Couleur';
		    			        		} else {
		    			        			
		    			        		}*/
				                	}
		            			}
		            		}
	    					
	    					for(cepage in doc.declaration.certifications[certification].genres[genre].appellations[appellation].mentions[mention].lieux[lieu].couleurs[couleur].cepages) 
	    					{    
	    						var cepageLibelle = doc.declaration.certifications[certification].genres[genre].appellations[appellation].mentions[mention].lieux[lieu].couleurs[couleur].cepages[cepage].libelle;
	    						var hash = "declaration/certifications/"+certification+"/genres/"+genre+"/appellations/"+appellation+"/mentions/"+mention+"/lieux/"+lieu+"/couleurs/"+couleur+"/cepages/"+cepage;
	    						
	    						for(i in interprosCouleur)
	    						{
	    							emit([i, certificationLibelle, genreLibelle, appellationLibelle, mentionLibelle, lieuLibelle, couleurLibelle, cepageLibelle, hash], {"departements": departementsLieu, "douane": interprosCouleur[i]["douane"], "cvo": interprosCouleur[i]["cvo"], "labels": interprosCouleur[i]["label"], "entrees": {"repli": detailLieu.entrees.repli.readable, "declassement": detailLieu.entrees.declassement.readable}, "sorties": {"repli": detailLieu.sorties.repli.readable, "declassement": detailLieu.sorties.declassement.readable}, "noeud_departements": noeudDepartements, "noeud_labels": noeudLabels, "noeud_douane": noeudDouane, "noeud_cvo": noeudCvo, "noeud_repli": noeudRepli, "noeud_declassement": noeudDeclassement});
	    						}
	    					}
	    				}
	    			}
    			}
    		}
        }
    }
    /*
     * Fonctions utiles
     */
    var fctHasDroits = function (noeud, interpo) {
    	return noeud.interpro[interpo].hasOwnProperty("droits");
    }
    var fctGetCvo = function (noeud, interpro) {
    	if (fctHasDroits(noeud, interpro)) {
    		if (noeud.interpro[interpo].droits.hasOwnProperty("cvo") && noeud.interpro[interpro].droits.cvo.length > 0 && noeud.interpro[interpro].droits.cvo[noeud.interpro[interpro].droits.cvo.length - 1].taux) {
    			return noeud.interpro[interpro].droits.cvo[noeud.interpro[interpro].droits.cvo.length - 1];
    		} else {
    			return null;
    		}
    	} else {
    		return null;
    	}
    }
    var fctGetDouane = function (noeud, interpro) {
    	if (fctHasDroits(noeud, interpro)) {
    		if (noeud.interpro[interpo].droits.hasOwnProperty("douane") && noeud.interpro[interpro].droits.douane.length > 0 && noeud.interpro[interpro].droits.douane[noeud.interpro[interpro].droits.douane.length - 1].taux) {
    			return noeud.interpro[interpro].droits.douane[noeud.interpro[interpro].droits.douane.length - 1];
    		} else {
    			return null;
    		}
    	} else {
    		return null;
    	}
    }
    var fctGetLabels = function (noeud, interpro) {
    	if (noeud.interpro[i].labels.length > 0) {
    		return noeud.interpro[i].labels;
    	} else {
    		return null;
    	}
    }
    var fctGetDetail = function (noeud) {
    	if (noeud.hasOwnProperty("detail")) {
    		if (noeud.detail.entrees.repli.readable && noeud.detail.entrees.repli.declassement && noeud.detail.sorties.repli.readable && noeud.detail.sorties.declassement.readable) {
    			return noeud.detail;
    		}
    	} else {
    		return null;
    	}
    }
}