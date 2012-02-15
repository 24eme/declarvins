/**
 * Fichier : drm.js
 * Description : fonctions JS spécifiques à la drm
 * Auteur : Hamza Iqbal - hiqbal[at]actualys.com
 * Copyright: Actualys
 ******************************************/

var objAjoutsLiquidations = {};

/**
 * Initialisation
 ******************************************/
(function($)
{
	var ajoutsLiquidations = $('#ajouts_liquidations');

	$(document).ready( function()
	{
		if(ajoutsLiquidations.exists()) $.initAjoutsLiquidations();
		
		 $('#ajouts_liquidations :checkbox').change(function() {
            $(this).parents('form').submit();
        });
		
        $('.updateProduct').submit(function() {
        	$.post($(this).attr('action'), $(this).serializeArray());
        	return false;
        });
	});
	
	
	/**
	 * Initialise les fonctions de l'étape
	 * Ajouts / Liquidations de la DRM
	 * $.initAjoutsLiquidations();
	 ******************************************/
	$.initAjoutsLiquidations = function()
	{
		var sections = ajoutsLiquidations.find('.tableau_ajouts_liquidations');
		
		$.extend(objAjoutsLiquidations,
		{
			sections: sections,
			tabSections: []
		});
		
		sections.each(function(i)
		{
			var section = $(this);
			var tabInfos = section.getInfosTableauProduits();
			
			objAjoutsLiquidations.tabSections.push(tabInfos);
			
			$.verifCoherenceStock(i);
			$.stylesTableaux(i);
			$.initSupressionProduit(i);
		});
		
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
	 * $.verifCoherenceStock();
	 ******************************************/
	$.verifCoherenceStock = function(i)
	{
		var lignes = objAjoutsLiquidations.tabSections[i].tableauRecapLignes;
		
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
	 * $.stylesTableaux(i);
	 ******************************************/
	$.stylesTableaux = function(i)
	{
		var tabSection = objAjoutsLiquidations.tabSections[i];
		var lignes = tabSection.tableauRecapLignes.not('.vide');
		var casesTableauRecap = tabSection.tableauRecapLignes.filter(':last').children('td');
		
		// Alternance de couleurs
		lignes.removeClass('alt');
		lignes.filter(':odd').addClass('alt');
	};
	

	/**
	 * Initialise le formulaire d'ajout d'un
	 * produit
	 * $(popup).initPopupAjoutProduit();
	 ******************************************/
	$.fn.initPopupAjoutProduit = function()
	{
		var popup = $(this);
		var form = popup.find('form');
		var parent = popup.parent();
		var selectMultiple = popup.find('.select_multiple');
		var formBtn = form.find('button');
		
		// Select multiple
		selectMultiple.dropdownchecklist({width: 200});

		// Soumission
		form.live('submit', function()
		{
			popup.addClass('popup_chargement');
			formBtn.attr('disabled', 'disabled');
			
			// Soumission AJAX
			$.post(form.attr('action'), form.serializeArray(), function (data)
			{
				popup.removeClass('popup_chargement');
				
				// S'il n'y a pas d'erreur -> Redirection
				if(data.success)
				{
					document.location.href = data.url;
				}
				// Sinon remplacement du formulaire par celui récupéré en AJAX
				else
				{
					popup.html(data.content);

					// Réinitialisation des fonctions
					selectMultiple = popup.find('.select_multiple');
					selectMultiple.dropdownchecklist({width: 200});
					form = popup.find('form');
					formBtn = form.find('button');
					formBtn.removeAttr('disabled');
				}
			}, "json");

            return false;
		});


		// Reinitialisation des champs et 
		// suppression des messages d'erreur à la fermeture
		popup.bind('fermer', function()
		{
			popup.find(':text').val('');
			popup.find('option').removeAttr('selected');
			popup.find(':checkbox,:radio').removeAttr('checked');
			popup.find('.ui-dropdownchecklist-selector .ui-dropdownchecklist-text').text('').attr('title','');
			popup.find('.error').remove();
		});
	};	
	
	/**
	 * Initialise la suppresion des produits
	 * $.initSupressionProduit(i);
	 ******************************************/
	$.initSupressionProduit = function(i)
	{
		var tabSection = objAjoutsLiquidations.tabSections[i];
		var btnSupprimer = tabSection.tableauRecapLignes.find('.supprimer');
		
		btnSupprimer.click(function()
		{
			var btn = $(this);
			var url = btn.attr('href');
			
			$.post(url, function()
			{
				// suppression
				btn.parents('tr').remove();
				
				// application des styles
				tabSection.tableauRecapLignes = tabSection.tableauRecap.find('tbody tr');
				$.stylesTableaux(i);
			});
			
        	return false;
		});
	};

})(jQuery);