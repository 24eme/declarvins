/**
 * Fichier : drm.js
 * Description : fonctions JS spécifiques à la drm
 * Auteur : Hamza Iqbal - hiqbal[at]actualys.com
 * Copyright: Actualys
 ******************************************/

/**
 * Initialisation
 ******************************************/
(function($)
{
	var ajoutsLiquidations = $('#ajouts_liquidations');
	var popupAjoutProduit = $('#popup_ajout_produit');

	$(document).ready( function()
	{
		if(ajoutsLiquidations.exists()) $.initAjoutsLiquidations();
	});
	
	
	/**
	 * Initialise les fonctions de l'étape
	 * Ajouts / Liquidations de la DRM
	 * $.initAjoutsLiquidations();
	 ******************************************/
	$.initAjoutsLiquidations = function()
	{
		var sections = ajoutsLiquidations.find('.tableau_ajouts_liquidations');
		
		sections.each(function()
		{
			var section = $(this);
			var tabInfos = section.getInfosTableauProduits();
			
			$.verifCoherenceStock(tabInfos.tableauRecapLignes);
			$.stylesTableaux(tabInfos);
		});
		
		$.initAjoutProduit();
	};
	
	
	/**
	 * Retourne toutes les informations liée
	 * à un tableau de produits
	 * $(s).getInfosTableauProduits();
	 ******************************************/
	$.fn.getInfosTableauProduits = function()
	{
		var tabInfos = {};
		var section = $(this);
		
		var blocRecapProduit = section.find('.recap_produit');
		var tableauRecap = blocRecapProduit.find('.tableau_recap');
		var tableauRecapLignes = tableauRecap.find('tbody tr');
		var tableauRecapChamps = tableauRecap.find('input, select');
		
		tabInfos = 
		{
			section: section,
			blocRecapProduit: blocRecapProduit,
			tableauRecap: tableauRecap,
			tableauRecapLignes: tableauRecapLignes
		};
		
		return tabInfos;
	};
	
	
	/**
	 * Vérifie la cohérence en disponibilité
	 * et stock vide pour les lignes des tableaux
	 * $.verifCoherenceStock(lignes);
	 ******************************************/
	$.verifCoherenceStock = function(lignes)
	{
		lignes.each(function()
		{
			var ligne = $(this);
			var disponible = ligne.find('td.disponible input');
			var stockVide = ligne.find('td.stock_vide input');

			if(parseFloat(disponible.val()) > 0) stockVide.attr('disabled', 'disabled');
			else  stockVide.removeAttr('disabled');
		});
	};
	
	
	/**
	 * Styles des tableaux
	 * $.stylesTableaux(tabInfos);
	 ******************************************/
	$.stylesTableaux = function(t)
	{
		var lignes = t.tableauRecapLignes.not('.vide');
		var casesTableauRecap = t.tableauRecapLignes.filter(':last').children('td');
		
		// Alternance de couleurs
		lignes.removeClass('alt');
		lignes.filter(':odd').addClass('alt');
	};
	
	/**
	 * Initialise le formulaire d'ajout d'un
	 * produit
	 * $.initAjoutProduit();
	 ******************************************/
	$.initAjoutProduit = function(t)
	{
		var disponible = popupAjoutProduit.find('#produit_disponible');
		var stockVide = popupAjoutProduit.find('#produit_stock_vide');
		var selectMultiple = popupAjoutProduit.find('.select_multiple');
		
		disponible.saisieNum(true, function()
		{
			if(parseFloat(disponible.val()) > 0) stockVide.attr('disabled','disabled');
			else stockVide.removeAttr('disabled');
			
			stockVide.removeAttr('checked');
		});
		
		selectMultiple.dropdownchecklist({width: 200});
	};

})(jQuery);