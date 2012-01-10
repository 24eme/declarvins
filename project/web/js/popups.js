/**
 * Fichier : popups.js
 * Description : fonctions JS spécifiques au popups 
 * Auteur : Hamza Iqbal - hiqbal[at]actualys.com
 * Copyright: Actualys
 ******************************************/

/**
 * Initialisation
 ******************************************/
(function($)
{
	var configPopup =
	{
		autoOpen: false,
		draggable: false,
		resizable: false,
		width: 460,
		modal: true
	};
	
	var configPopupAjoutProduit = 
	{
		close: function(event, ui)
		{
			// Réinitialise les champs à la fermeture
			var popup = $(this);
			popup.find(':text').val('');
			popup.find('option').removeAttr('selected');
			popup.find(':checkbox,:radio').removeAttr('checked');
			popup.find('.ui-dropdownchecklist-selector .ui-dropdownchecklist-text').text('').attr('title','');
		}
	};
	
	
	$(document).ready(function()
	{
		$.initPopups();
	});
	
	
	/**
	 * Initialisation de a fonction générique
	 * de créations de popups
	 * $.initPopups();
	 ******************************************/
	$.initPopups = function()
	{
		var btnsPopup = $('.btn_popup');
		
		btnsPopup.each(function()
		{
			var btnPopup = $(this);
			var popup = $(btnPopup.attr('data-popup'));
			var config = configPopup;
			var popupConfigSpec = btnPopup.attr('data-popup-config');
			var popupTitre = btnPopup.attr('data-popup-titre');
			var btnFermer = popup.find('.btn_fermer');
			
			if(!popupTitre) popupTitre = btnPopup.text();
			
			// Ajout du titre dynamique
			$.extend(config,
			{
				title: popupTitre
			});
			
			// Fusion avec la configuration spécifique
			if(popupConfigSpec)
			{
				$.extend(config, eval(popupConfigSpec));
			}
			
			popup.dialog(config);
			
			// Ouverture
			btnPopup.click(function()
			{

				alert('fuck');
				popup.dialog('open');
				return false;
			});
			
			// Fermeture
			btnFermer.click(function()
			{
				popup.dialog('close');
				return false;
			});
		});
	};
	
	
	
	
})(jQuery);