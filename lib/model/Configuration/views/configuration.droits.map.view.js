function(doc) 
{
    if (doc.type != "Configuration")
    	return ;
    var doc = doc;
    var nb = 0;
    for (certification in doc.declaration.certifications) 
    {
    	var interprosCertification = new Array();
    	for (i in doc.declaration.certifications[certification].interpro)
    	{
    		interprosCertification[i] = new Array();
    		interprosCertification[i]["douane"] = doc.declaration.certifications[certification].interpro[i].droits.douane[doc.declaration.certifications[certification].interpro[i].droits.douane.length - 1];
    		interprosCertification[i]["cvo"] = doc.declaration.certifications[certification].interpro[i].droits.cvo[doc.declaration.certifications[certification].interpro[i].droits.cvo.length - 1];
    	}
    	
    	for (genre in doc.declaration.certifications[certification].genres)
    	{
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
		        		interprosGenre[i]["douane"] = new Array();
		        		interprosGenre[i]["cvo"] = new Array();
		        		if (interprosCertification.hasOwnProperty(i))
		        		{
		        			interprosGenre[i]["douane"] = interprosCertification[i]["douane"];
		        			interprosGenre[i]["cvo"] = interprosCertification[i]["cvo"];
		        		}
		        		if (doc.declaration.certifications[certification].genres[genre].interpro[i].hasOwnProperty("droits"))
		        		{
		        			if (doc.declaration.certifications[certification].genres[genre].interpro[i].droits.hasOwnProperty("douane") && doc.declaration.certifications[certification].genres[genre].interpro[i].droits.douane.length > 0) 
		        			{ 
		        				interprosGenre[i]["douane"] = doc.declaration.certifications[certification].genres[genre].interpro[i].droits.douane[doc.declaration.certifications[certification].genres[genre].interpro[i].droits.douane.length - 1];
		        			}
		        			if (doc.declaration.certifications[certification].genres[genre].interpro[i].droits.hasOwnProperty("cvo") && doc.declaration.certifications[certification].genres[genre].interpro[i].droits.cvo.length > 0) 
		        			{
		        				interprosGenre[i]["cvo"] = doc.declaration.certifications[certification].genres[genre].interpro[i].droits.cvo[doc.declaration.certifications[certification].genres[genre].interpro[i].droits.cvo.length - 1];
		        			}
		        		}
		        	}
    			}
    		}
    		for (appellation in doc.declaration.certifications[certification].genres[genre].appellations) 
    		{
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
		            		interprosAppellation[i]["douane"] = new Array();
		            		interprosAppellation[i]["cvo"] = new Array();
		            		if (interprosGenre.hasOwnProperty(i))
		            		{
		            			interprosAppellation[i]["douane"] = interprosGenre[i]["douane"];
		            			interprosAppellation[i]["cvo"] = interprosGenre[i]["cvo"];
		            		}
		            		if (doc.declaration.certifications[certification].genres[genre].appellations[appellation].interpro[i].hasOwnProperty("droits"))
		            		{
		            			if (doc.declaration.certifications[certification].genres[genre].appellations[appellation].interpro[i].droits.hasOwnProperty("douane") && doc.declaration.certifications[certification].genres[genre].appellations[appellation].interpro[i].droits.douane.length > 0) 
		            			{
		            				interprosAppellation[i]["douane"] = doc.declaration.certifications[certification].genres[genre].appellations[appellation].interpro[i].droits.douane[doc.declaration.certifications[certification].genres[genre].appellations[appellation].interpro[i].droits.douane.length - 1];
		            			}
		            			if (doc.declaration.certifications[certification].genres[genre].appellations[appellation].interpro[i].droits.hasOwnProperty("cvo") && doc.declaration.certifications[certification].genres[genre].appellations[appellation].interpro[i].droits.cvo.length > 0) 
		            			{
		            				interprosAppellation[i]["cvo"] = doc.declaration.certifications[certification].genres[genre].appellations[appellation].interpro[i].droits.cvo[doc.declaration.certifications[certification].genres[genre].appellations[appellation].interpro[i].droits.cvo.length - 1];
		            			}
		            		}
		            	}
        			}
        		}
        		for(mention in doc.declaration.certifications[certification].genres[genre].appellations[appellation].mentions) 
        		{
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
        		        		interprosMention[i]["douane"] = new Array();
        		        		interprosMention[i]["cvo"] = new Array();
        		        		if (interprosAppellation.hasOwnProperty(i))
        		        		{
        		        			interprosMention[i]["douane"] = interprosAppellation[i]["douane"];
        		        			interprosMention[i]["cvo"] = interprosAppellation[i]["cvo"];
        		        		}
        		        		if (doc.declaration.certifications[certification].genres[genre].appellations[appellation].mentions[mention].interpro[i].hasOwnProperty("droits"))
        		        		{
        		        			if (doc.declaration.certifications[certification].genres[genre].appellations[appellation].mentions[mention].interpro[i].droits.hasOwnProperty("douane") && doc.declaration.certifications[certification].genres[genre].appellations[appellation].mentions[mention].interpro[i].droits.douane.length > 0) 
        		        			{
        		        				interprosMention[i]["douane"] = doc.declaration.certifications[certification].genres[genre].appellations[appellation].mentions[mention].interpro[i].droits.douane[doc.declaration.certifications[certification].genres[genre].appellations[appellation].mentions[mention].interpro[i].droits.douane.length - 1];
        		        			}
        		        			if (doc.declaration.certifications[certification].genres[genre].appellations[appellation].mentions[mention].interpro[i].droits.hasOwnProperty("cvo") && doc.declaration.certifications[certification].genres[genre].appellations[appellation].mentions[mention].interpro[i].droits.cvo.length > 0) 
        		        			{
        		        				interprosMention[i]["cvo"] = doc.declaration.certifications[certification].genres[genre].appellations[appellation].mentions[mention].interpro[i].droits.cvo[doc.declaration.certifications[certification].genres[genre].appellations[appellation].mentions[mention].interpro[i].droits.cvo.length - 1];
        		        			}
        		        		}
        		        	}
        				}
        			}
	    			for(lieu in doc.declaration.certifications[certification].genres[genre].appellations[appellation].mentions[mention].lieux) 
	    			{
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
			                		interprosLieu[i]["douane"] = new Array();
			                		interprosLieu[i]["cvo"] = new Array();
			                		if (interprosMention.hasOwnProperty(i))
			                		{
			                			interprosLieu[i]["douane"] = interprosMention[i]["douane"];
			                			interprosLieu[i]["cvo"] = interprosMention[i]["cvo"];
			                		}
			                		if (doc.declaration.certifications[certification].genres[genre].appellations[appellation].mentions[mention].lieux[lieu].interpro[i].hasOwnProperty("droits"))
			                		{
				            			if (doc.declaration.certifications[certification].genres[genre].appellations[appellation].mentions[mention].lieux[lieu].interpro[i].droits.hasOwnProperty("douane") && doc.declaration.certifications[certification].genres[genre].appellations[appellation].mentions[mention].lieux[lieu].interpro[i].droits.douane.length > 0) 
				            			{
				            				interprosLieu[i]["douane"] = doc.declaration.certifications[certification].genres[genre].appellations[appellation].mentions[mention].lieux[lieu].interpro[i].droits.douane[doc.declaration.certifications[certification].genres[genre].appellations[appellation].mentions[mention].lieux[lieu].interpro[i].droits.douane.length - 1];
				            			}
				            			if (doc.declaration.certifications[certification].genres[genre].appellations[appellation].mentions[mention].lieux[lieu].interpro[i].droits.hasOwnProperty("cvo") && doc.declaration.certifications[certification].genres[genre].appellations[appellation].mentions[mention].lieux[lieu].interpro[i].droits.cvo.length > 0) 
				            			{
				            				interprosLieu[i]["cvo"] = doc.declaration.certifications[certification].genres[genre].appellations[appellation].mentions[mention].lieux[lieu].interpro[i].droits.cvo[doc.declaration.certifications[certification].genres[genre].appellations[appellation].mentions[mention].lieux[lieu].interpro[i].droits.cvo.length - 1];
				            			}
			                		}
			                	}
	            			}
	            		}	    				
	    				for(couleur in doc.declaration.certifications[certification].genres[genre].appellations[appellation].mentions[mention].lieux[lieu].couleurs) 
	    				{
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
				                		interprosCouleur[i]["douane"] = new Array();
				                		interprosCouleur[i]["cvo"] = new Array();
				                		if (interprosLieu.hasOwnProperty(i))
				                		{
				                			interprosCouleur[i]["douane"] = interprosLieu[i]["douane"];
				                			interprosCouleur[i]["cvo"] = interprosLieu[i]["cvo"];
				                		}
				                		if (doc.declaration.certifications[certification].genres[genre].appellations[appellation].mentions[mention].lieux[lieu].couleurs[couleur].interpro[i].hasOwnProperty("droits"))
				                		{
					            			if (doc.declaration.certifications[certification].genres[genre].appellations[appellation].mentions[mention].lieux[lieu].couleurs[couleur].interpro[i].droits.hasOwnProperty("douane") && doc.declaration.certifications[certification].genres[genre].appellations[appellation].mentions[mention].lieux[lieu].couleurs[couleur].interpro[i].droits.douane.length > 0) 
					            			{
					            				interprosCouleur[i]["douane"] = doc.declaration.certifications[certification].genres[genre].appellations[appellation].mentions[mention].lieux[lieu].couleurs[couleur].interpro[i].droits.douane[doc.declaration.certifications[certification].genres[genre].appellations[appellation].mentions[mention].lieux[lieu].couleurs[couleur].interpro[i].droits.douane.length - 1];
					            			}
					            			if (doc.declaration.certifications[certification].genres[genre].appellations[appellation].mentions[mention].lieux[lieu].couleurs[couleur].interpro[i].droits.hasOwnProperty("cvo") && doc.declaration.certifications[certification].genres[genre].appellations[appellation].mentions[mention].lieux[lieu].couleurs[couleur].interpro[i].droits.cvo.length > 0) 
					            			{
					            				interprosCouleur[i]["cvo"] = doc.declaration.certifications[certification].genres[genre].appellations[appellation].mentions[mention].lieux[lieu].couleurs[couleur].interpro[i].droits.cvo[doc.declaration.certifications[certification].genres[genre].appellations[appellation].mentions[mention].lieux[lieu].couleurs[couleur].interpro[i].droits.cvo.length - 1];
					            			}
				                		}
				                	}
		            			}
		            		}
	    					for(cepage in doc.declaration.certifications[certification].genres[genre].appellations[appellation].mentions[mention].lieux[lieu].couleurs[couleur].cepages) 
	    					{    
	    						var hash = "declaration/certifications/"+certification+"/genres/"+genre+"/appellations/"+appellation+"/mentions/"+mention+"/lieux/"+lieu+"/couleurs/"+couleur+"/cepages/"+cepage;
	    						for(i in interprosCouleur)
	    						{
	    							emit([hash], {"douane": interprosCouleur[i]["douane"], "cvo": interprosCouleur[i]["cvo"]});
	    						}
	    					}
	    				}
	    			}
    			}
    		}
        }
    }
}