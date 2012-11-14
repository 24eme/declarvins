function(doc) 
{
    var fctArrayCopy = function (arrayToCopy) {
    	var newTab = new Array();
    	for(var i in arrayToCopy) {
    		newTab[i] = arrayToCopy[i];
    	}
    	return newTab;
    }
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
    var nb = 0;
    
    for (certification in doc.declaration.certifications) 
    {
    	var certificationLibelle = doc.declaration.certifications[certification].libelle;
    	var departementsCertification = doc.declaration.certifications[certification].departements;
    	var detailCertification = doc.declaration.certifications[certification].detail;
    	var interprosCertification = new Array();
    	var bCertification = new Array();
        bCertification["departements"] = 'Catégorie';
        bCertification["douane"] = 'Catégorie';
        bCertification["cvo"] = 'Catégorie';
        bCertification["labels"] = 'Catégorie';
        bCertification["details"] = 'Catégorie';
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
    		var interprosGenre = fctArrayMultiCopy(interprosCertification);
    		var bGenre = fctArrayCopy(bCertification);
    		
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
		        		if (doc.declaration.certifications[certification].genres[genre].interpro[i].labels.length > 0)
		        		{
		        			interprosGenre[i]["label"] = doc.declaration.certifications[certification].genres[genre].interpro[i].labels;
		        			bGenre["labels"] = 'Genre';
		        		} else {
		        			bGenre["labels"] = bCertification["labels"];
		        		}
		        		if (doc.declaration.certifications[certification].genres[genre].interpro[i].hasOwnProperty("droits"))
		        		{
		        			if (doc.declaration.certifications[certification].genres[genre].interpro[i].droits.hasOwnProperty("douane") && doc.declaration.certifications[certification].genres[genre].interpro[i].droits.douane.length > 0) 
		        			{ 
		        				if (doc.declaration.certifications[certification].genres[genre].interpro[i].droits.douane[doc.declaration.certifications[certification].genres[genre].interpro[i].droits.douane.length - 1].taux) {
		        					interprosGenre[i]["douane"] = doc.declaration.certifications[certification].genres[genre].interpro[i].droits.douane[doc.declaration.certifications[certification].genres[genre].interpro[i].droits.douane.length - 1];
		        					bGenre["douane"] = 'Genre';
		        				} else {
		        					bGenre["douane"] = bCertification["douane"];
		        				}
		        			} else {
	        					bGenre["douane"] = bCertification["douane"];
	        				}
		        			if (doc.declaration.certifications[certification].genres[genre].interpro[i].droits.hasOwnProperty("cvo") && doc.declaration.certifications[certification].genres[genre].interpro[i].droits.cvo.length > 0) 
		        			{
		        				if (doc.declaration.certifications[certification].genres[genre].interpro[i].droits.cvo[doc.declaration.certifications[certification].genres[genre].interpro[i].droits.cvo.length - 1].taux) {
		        					interprosGenre[i]["cvo"] = doc.declaration.certifications[certification].genres[genre].interpro[i].droits.cvo[doc.declaration.certifications[certification].genres[genre].interpro[i].droits.cvo.length - 1];
		        					bGenre["cvo"] = 'Genre';
		        				} else {
		        					bGenre["cvo"] = bCertification["cvo"];
		        				}
		        			} else {
	        					bGenre["cvo"] = bCertification["cvo"];
	        				}
		        		} else {
		        			bGenre["douane"] = bCertification["douane"];
        					bGenre["cvo"] = bCertification["cvo"];
        				}
		        	}
    			}
    		}
    		if (departementsGenre.length == 0)
    		{
    			departementsGenre = departementsCertification;
    			bGenre["departements"] = bCertification["departements"];
    		}
    		else
    		{
    			bGenre["departements"] = 'Genre';
    		}
    		if (doc.declaration.certifications[certification].genres[genre].hasOwnProperty('detail'))
    		{
    			if (doc.declaration.certifications[certification].genres[genre].detail.entrees.repli.readable && doc.declaration.certifications[certification].genres[genre].detail.entrees.repli.declassement && doc.declaration.certifications[certification].genres[genre].detail.sorties.repli.readable && doc.declaration.certifications[certification].genres[genre].detail.sorties.declassement.readable) {
	    			detailGenre = doc.declaration.certifications[certification].genres[genre].detail;
	    			bGenre["details"] = 'Genre';
    			} else {
    				bGenre["details"] = bCertification["details"];
    			}
    		} else {
				bGenre["details"] = bCertification["details"];
			}
    		for (appellation in doc.declaration.certifications[certification].genres[genre].appellations) 
    		{
    			var appellationLibelle = doc.declaration.certifications[certification].genres[genre].appellations[appellation].libelle;
        		var departementsAppellation = doc.declaration.certifications[certification].genres[genre].appellations[appellation].departements;
        		var detailAppellation = detailGenre;
        		var interprosAppellation = fctArrayMultiCopy(interprosGenre);
        		var bAppellation = fctArrayCopy(bGenre);
        		
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
		            		if (doc.declaration.certifications[certification].genres[genre].appellations[appellation].interpro[i].labels.length > 0)
		            		{
			        			interprosAppellation[i]["label"] = doc.declaration.certifications[certification].genres[genre].appellations[appellation].interpro[i].labels;
			        	        bAppellation["labels"] = 'Dénomination';
		            		} else {
		            			bAppellation["labels"] = bGenre["labels"];
		            		}
		            		if (doc.declaration.certifications[certification].genres[genre].appellations[appellation].interpro[i].hasOwnProperty("droits"))
		            		{
		            			if (doc.declaration.certifications[certification].genres[genre].appellations[appellation].interpro[i].droits.hasOwnProperty("douane") && doc.declaration.certifications[certification].genres[genre].appellations[appellation].interpro[i].droits.douane.length > 0) 
		            			{
		            				if (doc.declaration.certifications[certification].genres[genre].appellations[appellation].interpro[i].droits.douane[doc.declaration.certifications[certification].genres[genre].appellations[appellation].interpro[i].droits.douane.length - 1].taux) {
		            					interprosAppellation[i]["douane"] = doc.declaration.certifications[certification].genres[genre].appellations[appellation].interpro[i].droits.douane[doc.declaration.certifications[certification].genres[genre].appellations[appellation].interpro[i].droits.douane.length - 1];
		            					bAppellation["douane"] = 'Dénomination';
		            				} else {
		            					bAppellation["douane"] = bGenre["douane"];
		            				}
		            			} else {
	            					bAppellation["douane"] = bGenre["douane"];
	            				}
		            			if (doc.declaration.certifications[certification].genres[genre].appellations[appellation].interpro[i].droits.hasOwnProperty("cvo") && doc.declaration.certifications[certification].genres[genre].appellations[appellation].interpro[i].droits.cvo.length > 0) 
		            			{
		            				if (doc.declaration.certifications[certification].genres[genre].appellations[appellation].interpro[i].droits.cvo[doc.declaration.certifications[certification].genres[genre].appellations[appellation].interpro[i].droits.cvo.length - 1].taux) {
			            				interprosAppellation[i]["cvo"] = doc.declaration.certifications[certification].genres[genre].appellations[appellation].interpro[i].droits.cvo[doc.declaration.certifications[certification].genres[genre].appellations[appellation].interpro[i].droits.cvo.length - 1];
			            				bAppellation["cvo"] = 'Dénomination';
		            				} else {
		            					bAppellation["cvo"] = bGenre["cvo"];
		            				}
		            			} else {
	            					bAppellation["cvo"] = bGenre["cvo"];
	            				}
		            		} else {
            					bAppellation["douane"] = bGenre["douane"];
            					bAppellation["cvo"] = bGenre["cvo"];
            				}
		            	}
        			}
        		}
        		if (departementsAppellation.length == 0)
        		{
        			departementsAppellation = departementsGenre;
        			bAppellation["departements"] = bGenre["departements"];
        		}
        		else
        		{
        			bAppellation["departements"] = 'Dénomination';
        		}
        		if (doc.declaration.certifications[certification].genres[genre].appellations[appellation].hasOwnProperty('detail'))
        		{
        			if (doc.declaration.certifications[certification].genres[genre].appellations[appellation].detail.entrees.repli.readable && doc.declaration.certifications[certification].genres[genre].appellations[appellation].detail.entrees.repli.declassement && doc.declaration.certifications[certification].genres[genre].appellations[appellation].detail.sorties.repli.readable && doc.declaration.certifications[certification].genres[genre].appellations[appellation].detail.sorties.declassement.readable) {
	        			detailAppellation = doc.declaration.certifications[certification].genres[genre].appellations[appellation].detail;
	        			bAppellation["details"] = 'Dénomination';
        			} else {
        				bAppellation["details"] = bGenre["details"];
        			}
        		} else {
    				bAppellation["details"] = bGenre["details"];
    			}
        		for(mention in doc.declaration.certifications[certification].genres[genre].appellations[appellation].mentions) 
        		{
        			var mentionLibelle = doc.declaration.certifications[certification].genres[genre].appellations[appellation].mentions[mention].libelle;
        			var departementsMention = doc.declaration.certifications[certification].genres[genre].appellations[appellation].mentions[mention].departements;
        			var detailMention = detailAppellation;
        			var interprosMention = fctArrayMultiCopy(interprosAppellation);
        			var bMention = fctArrayCopy(bAppellation);
            		
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
        		        		if (doc.declaration.certifications[certification].genres[genre].appellations[appellation].mentions[mention].interpro[i].labels.length > 0)
        		        		{
        		        			interprosMention[i]["label"] = doc.declaration.certifications[certification].genres[genre].appellations[appellation].mentions[mention].interpro[i].labels;
        		        	        bMention["labels"] = 'Mention';
        		        		} else {
        		        			bMention["labels"] = bAppellation["labels"];
        		        		}
        		        		if (doc.declaration.certifications[certification].genres[genre].appellations[appellation].mentions[mention].interpro[i].hasOwnProperty("droits"))
        		        		{
        		        			if (doc.declaration.certifications[certification].genres[genre].appellations[appellation].mentions[mention].interpro[i].droits.hasOwnProperty("douane") && doc.declaration.certifications[certification].genres[genre].appellations[appellation].mentions[mention].interpro[i].droits.douane.length > 0) 
        		        			{
        		        				if (doc.declaration.certifications[certification].genres[genre].appellations[appellation].mentions[mention].interpro[i].droits.douane[doc.declaration.certifications[certification].genres[genre].appellations[appellation].mentions[mention].interpro[i].droits.douane.length - 1].taux) {
        		        					interprosMention[i]["douane"] = doc.declaration.certifications[certification].genres[genre].appellations[appellation].mentions[mention].interpro[i].droits.douane[doc.declaration.certifications[certification].genres[genre].appellations[appellation].mentions[mention].interpro[i].droits.douane.length - 1];
        		        					bMention["douane"] = 'Mention';
        		        				} else {
        		        					bMention["douane"] = bAppellation["douane"];
        		        				}
        		        			} else {
    		        					bMention["douane"] = bAppellation["douane"];
    		        				}
        		        			if (doc.declaration.certifications[certification].genres[genre].appellations[appellation].mentions[mention].interpro[i].droits.hasOwnProperty("cvo") && doc.declaration.certifications[certification].genres[genre].appellations[appellation].mentions[mention].interpro[i].droits.cvo.length > 0) 
        		        			{
        		        				if (doc.declaration.certifications[certification].genres[genre].appellations[appellation].mentions[mention].interpro[i].droits.cvo[doc.declaration.certifications[certification].genres[genre].appellations[appellation].mentions[mention].interpro[i].droits.cvo.length - 1].taux) {
        		        					interprosMention[i]["cvo"] = doc.declaration.certifications[certification].genres[genre].appellations[appellation].mentions[mention].interpro[i].droits.cvo[doc.declaration.certifications[certification].genres[genre].appellations[appellation].mentions[mention].interpro[i].droits.cvo.length - 1];
        		            	        	bMention["cvo"] = 'Mention';
        		        				} else {
        		        					bMention["cvo"] = bAppellation["cvo"];
        		        				}
        		        			} else {
    		        					bMention["cvo"] = bAppellation["cvo"];
    		        				}
        		        		} else {
		        					bMention["douane"] = bAppellation["douane"];
		        					bMention["cvo"] = bAppellation["cvo"];
		        				}
        		        	}
        				}
        			}
        			if (departementsMention.length == 0)
        			{
        				departementsMention = departementsAppellation;
        				bMention["departements"] = bAppellation["departements"];
        			}
        			else
        			{
        				bMention["departements"] = 'Mention';
        			}
        			if (doc.declaration.certifications[certification].genres[genre].appellations[appellation].mentions[mention].hasOwnProperty('detail'))
        			{
        				if (doc.declaration.certifications[certification].genres[genre].appellations[appellation].mentions[mention].detail.entrees.repli.readable && doc.declaration.certifications[certification].genres[genre].appellations[appellation].mentions[mention].detail.entrees.repli.declassement && doc.declaration.certifications[certification].genres[genre].appellations[appellation].mentions[mention].detail.sorties.repli.readable && doc.declaration.certifications[certification].genres[genre].appellations[appellation].mentions[mention].detail.sorties.declassement.readable) {
	        				detailLieu = doc.declaration.certifications[certification].genres[genre].appellations[appellation].mentions[mention].detail;
	        				bMention["details"] = 'Mention';
        				} else {
        					bMention["details"] = bAppellation["details"];
        				}
        			} else {
    					bMention["details"] = bAppellation["details"];
    				}
	    			for(lieu in doc.declaration.certifications[certification].genres[genre].appellations[appellation].mentions[mention].lieux) 
	    			{
	    				var lieuLibelle = doc.declaration.certifications[certification].genres[genre].appellations[appellation].mentions[mention].lieux[lieu].libelle;
	            		var departementsLieu = doc.declaration.certifications[certification].genres[genre].appellations[appellation].mentions[mention].lieux[lieu].departements;
	            		var detailLieu = detailMention;
	            		var interprosLieu = fctArrayMultiCopy(interprosMention);
	            		var bLieu = fctArrayCopy(bMention);
	            		
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
			                		if (doc.declaration.certifications[certification].genres[genre].appellations[appellation].mentions[mention].lieux[lieu].interpro[i].labels.length > 0)
			                		{
					        			interprosLieu[i]["label"] = doc.declaration.certifications[certification].genres[genre].appellations[appellation].mentions[mention].lieux[lieu].interpro[i].labels;
					        	        bLieu["labels"] = 'Lieu';
			                		} else {
			                			bLieu["labels"] = bMention["labels"];
			                		}
			                		if (doc.declaration.certifications[certification].genres[genre].appellations[appellation].mentions[mention].lieux[lieu].interpro[i].hasOwnProperty("droits"))
			                		{
				            			if (doc.declaration.certifications[certification].genres[genre].appellations[appellation].mentions[mention].lieux[lieu].interpro[i].droits.hasOwnProperty("douane") && doc.declaration.certifications[certification].genres[genre].appellations[appellation].mentions[mention].lieux[lieu].interpro[i].droits.douane.length > 0) 
				            			{
				            				if (doc.declaration.certifications[certification].genres[genre].appellations[appellation].mentions[mention].lieux[lieu].interpro[i].droits.douane[doc.declaration.certifications[certification].genres[genre].appellations[appellation].mentions[mention].lieux[lieu].interpro[i].droits.douane.length - 1].taux) {
				            					interprosLieu[i]["douane"] = doc.declaration.certifications[certification].genres[genre].appellations[appellation].mentions[mention].lieux[lieu].interpro[i].droits.douane[doc.declaration.certifications[certification].genres[genre].appellations[appellation].mentions[mention].lieux[lieu].interpro[i].droits.douane.length - 1];
				            					bLieu["douane"] = 'Lieu';
				            				} else {
				            					bLieu["douane"] = bMention["douane"];
				            				}
				            			} else {
			            					bLieu["douane"] = bMention["douane"];
			            				}
				            			if (doc.declaration.certifications[certification].genres[genre].appellations[appellation].mentions[mention].lieux[lieu].interpro[i].droits.hasOwnProperty("cvo") && doc.declaration.certifications[certification].genres[genre].appellations[appellation].mentions[mention].lieux[lieu].interpro[i].droits.cvo.length > 0) 
				            			{
				            				if (doc.declaration.certifications[certification].genres[genre].appellations[appellation].mentions[mention].lieux[lieu].interpro[i].droits.cvo[doc.declaration.certifications[certification].genres[genre].appellations[appellation].mentions[mention].lieux[lieu].interpro[i].droits.cvo.length - 1].taux) {
				            					interprosLieu[i]["cvo"] = doc.declaration.certifications[certification].genres[genre].appellations[appellation].mentions[mention].lieux[lieu].interpro[i].droits.cvo[doc.declaration.certifications[certification].genres[genre].appellations[appellation].mentions[mention].lieux[lieu].interpro[i].droits.cvo.length - 1];
				            					bLieu["cvo"] = 'Lieu';
				            				} else {
				            					bLieu["cvo"] = bMention["cvo"];
				            				}
				            			} else {
			            					bLieu["cvo"] = bMention["cvo"];
			            				}
			                		} else {
			                			bLieu["douane"] = bMention["douane"];
		            					bLieu["cvo"] = bMention["cvo"];
		            				}
			                	}
	            			}
	            		}
	            		if (departementsLieu.length == 0)
	            		{
	            			departementsLieu = departementsMention;
	            			bLieu["departements"] = bMention["departements"];
	            		} 
	            		else 
	            		{
	            			bLieu["departements"] = 'Lieu';
	            		}
	            		if (doc.declaration.certifications[certification].genres[genre].appellations[appellation].mentions[mention].lieux[lieu].hasOwnProperty('detail'))
	            		{
	            			if (doc.declaration.certifications[certification].genres[genre].appellations[appellation].mentions[mention].lieux[lieu].detail.entrees.repli.readable && doc.declaration.certifications[certification].genres[genre].appellations[appellation].mentions[mention].lieux[lieu].detail.entrees.repli.declassement && doc.declaration.certifications[certification].genres[genre].appellations[appellation].mentions[mention].lieux[lieu].detail.sorties.repli.readable && doc.declaration.certifications[certification].genres[genre].appellations[appellation].mentions[mention].lieux[lieu].detail.sorties.declassement.readable) {
	            				detailLieu = doc.declaration.certifications[certification].genres[genre].appellations[appellation].mentions[mention].lieux[lieu].detail;
	            				bLieu["details"] = 'Lieu';
	            			} else {
	            				bLieu["details"] = bMention["details"];
	            			}
	            		} else {
	            			bLieu["details"] = bMention["details"];
            			}
	    				
	    				for(couleur in doc.declaration.certifications[certification].genres[genre].appellations[appellation].mentions[mention].lieux[lieu].couleurs) 
	    				{
	    					var couleurLibelle = doc.declaration.certifications[certification].genres[genre].appellations[appellation].mentions[mention].lieux[lieu].couleurs[couleur].libelle;
		            		var interprosCouleur = fctArrayMultiCopy(interprosLieu);
		            		var bCouleur = fctArrayCopy(bLieu);
		            		
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
				                		if (doc.declaration.certifications[certification].genres[genre].appellations[appellation].mentions[mention].lieux[lieu].couleurs[couleur].interpro[i].hasOwnProperty("droits"))
				                		{
					            			if (doc.declaration.certifications[certification].genres[genre].appellations[appellation].mentions[mention].lieux[lieu].couleurs[couleur].interpro[i].droits.hasOwnProperty("douane") && doc.declaration.certifications[certification].genres[genre].appellations[appellation].mentions[mention].lieux[lieu].couleurs[couleur].interpro[i].droits.douane.length > 0) 
					            			{
					            				if (doc.declaration.certifications[certification].genres[genre].appellations[appellation].mentions[mention].lieux[lieu].couleurs[couleur].interpro[i].droits.douane[doc.declaration.certifications[certification].genres[genre].appellations[appellation].mentions[mention].lieux[lieu].couleurs[couleur].interpro[i].droits.douane.length - 1].taux) {
					            					interprosCouleur[i]["douane"] = doc.declaration.certifications[certification].genres[genre].appellations[appellation].mentions[mention].lieux[lieu].couleurs[couleur].interpro[i].droits.douane[doc.declaration.certifications[certification].genres[genre].appellations[appellation].mentions[mention].lieux[lieu].couleurs[couleur].interpro[i].droits.douane.length - 1];
					            					bCouleur["douane"] = 'Couleur';
					            				} else {
					            					bCouleur["douane"] = bLieu["douane"];
					            				}
					            			} else {
					            				bCouleur["douane"] = bLieu["douane"];
				            				}
					            			if (doc.declaration.certifications[certification].genres[genre].appellations[appellation].mentions[mention].lieux[lieu].couleurs[couleur].interpro[i].droits.hasOwnProperty("cvo") && doc.declaration.certifications[certification].genres[genre].appellations[appellation].mentions[mention].lieux[lieu].couleurs[couleur].interpro[i].droits.cvo.length > 0) 
					            			{
					            				if (doc.declaration.certifications[certification].genres[genre].appellations[appellation].mentions[mention].lieux[lieu].couleurs[couleur].interpro[i].droits.cvo[doc.declaration.certifications[certification].genres[genre].appellations[appellation].mentions[mention].lieux[lieu].couleurs[couleur].interpro[i].droits.cvo.length - 1].taux) {
					            					interprosCouleur[i]["cvo"] = doc.declaration.certifications[certification].genres[genre].appellations[appellation].mentions[mention].lieux[lieu].couleurs[couleur].interpro[i].droits.cvo[doc.declaration.certifications[certification].genres[genre].appellations[appellation].mentions[mention].lieux[lieu].couleurs[couleur].interpro[i].droits.cvo.length - 1];
					            					bCouleur["cvo"] = 'Couleur';
					            				} else {
					            					bCouleur["cvo"] = bLieu["cvo"];
					            				}
					            			} else {
					            				bCouleur["cvo"] = bLieu["cvo"];
				            				}
				                		} else {
				                			bCouleur["douane"] = bLieu["douane"];
				                			bCouleur["cvo"] = bLieu["cvo"];
			            				}
				                	}
		            			}
		            		}
	    					
	    					for(cepage in doc.declaration.certifications[certification].genres[genre].appellations[appellation].mentions[mention].lieux[lieu].couleurs[couleur].cepages) 
	    					{    
	    						var cepageLibelle = doc.declaration.certifications[certification].genres[genre].appellations[appellation].mentions[mention].lieux[lieu].couleurs[couleur].cepages[cepage].libelle;
	    						var hash = "declaration/certifications/"+certification+"/genres/"+genre+"/appellations/"+appellation+"/mentions/"+mention+"/lieux/"+lieu+"/couleurs/"+couleur+"/cepages/"+cepage;
	    						
	    						for(i in interprosCouleur)
	    						{
	    							emit([i, certificationLibelle, genreLibelle, appellationLibelle, mentionLibelle, lieuLibelle, couleurLibelle, cepageLibelle, hash], {"departements": departementsLieu, "douane": interprosCouleur[i]["douane"], "cvo": interprosCouleur[i]["cvo"], "labels": interprosCouleur[i]["label"], "entrees": {"repli": detailLieu.entrees.repli.readable, "declassement": detailLieu.entrees.declassement.readable}, "sorties": {"repli": detailLieu.sorties.repli.readable, "declassement": detailLieu.sorties.declassement.readable}, "noeud_departements": bCouleur["departements"], "noeud_labels": bCouleur["labels"], "noeud_douane": bCouleur["douane"], "noeud_cvo": bCouleur["cvo"], "noeud_repli": bCouleur["details"], "noeud_declassement": bCouleur["details"]});
	    						}
	    					}
	    				}
	    			}
    			}
    		}
        }
    }
}