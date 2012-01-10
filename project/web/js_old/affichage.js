/**
 * Fichier : affichage.js
 * Description : fonctions JS spécifiques à
 * la présentation du site
 * Auteur : Hamza Iqbal - hiqbal[at]actualys.com
 * Copyright: Actualys
 ******************************************/

/**
 * Initialisation
 ******************************************/
$(document).ready( function()
{
	// Corectifs IE
	if($.browser.msie)
	{
		try
		{
			/**
			 * Coins arrondis
			 ******************************************/
			if(typeof DD_roundies.addRule == 'function')
				DD_roundies.addRule('.bloc_arrondi', '3px');
			
			/**
			 * PNG fix
			 ******************************************/
			if(typeof DD_belatedPNG.fix == 'function' && parseInt($.browser.version) <= 6)
				DD_belatedPNG.fix('.pngfix');
		}
		catch(e) {}
	}
});