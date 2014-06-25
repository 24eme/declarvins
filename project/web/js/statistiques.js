/**
 * Fichier : statistiques.js
 * Description : fonctions JS spécifiques aux statistiques
 * Auteur : Mikaël Guillin - mguillin[at]actualys.com
 * Copyright: Actualys
 ******************************************/

(function($)
{
	
	var statsConteneur = $('#contenu.statistiques');
	
	$(document).ready(function()
	{
				
		statsConteneur.find("#filtre_produits_items select").combobox();
		
	});
})(jQuery);