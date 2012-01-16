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
	var objPopups = {}
	
	$.extend(objPopups,
	{
		infosPopups: [],
		configDefaut:
		{
			autoOpen: false,
			draggable: false,
			resizable: false,
			width: 460,
			modal: true
		},
		configAjoutProduit:
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
		}
	});
	
	
	$(document).ready(function()
	{
		$.initPopups();
	});
	
	/**
	 * Initialisation de la fonction générique
	 * de créations de popups
	 * $.initPopups();
	 ******************************************/
	$.initPopups = function()
	{
		var btnsPopup = $('.btn_popup');
		
		btnsPopup.each(function()
		{
			var btnPopup = $(this);
			var popupId = btnPopup.attr('data-popup');
			var popupConfigSpec = btnPopup.attr('data-popup-config');
			var popupTitre = btnPopup.attr('data-popup-titre');
			var ajax = btnPopup.attr('data-popup-ajax');
			var href = btnPopup.attr('href');
			var popup = $(popupId);
			
			var infosPopup =
			{
				btnPopup: btnPopup,
				btnPopupTexte: btnPopup.text(),
				config: objPopups.configDefaut,
				popupId: popupId,
				popup: popup,
				popupConfigSpec: popupConfigSpec,
				popupTitre: popupTitre,
				ajax: ajax,
				href: href
			};
			
			
			// Si le contenu est à charger en ajax
			if(ajax && ajax == "true")
			{
				$.get(href, function(reponse)
				{
					// On n'insère pas une popup déjà présente
					if($(infosPopup.popupId).size() == 0)
					{
						$('body').append(reponse.content);
					}
					
					infosPopup.popup = $(infosPopup.popupId);
					infosPopup = $.initActionsPopup(infosPopup);
					
				});
			}
			else
			{
				infosPopup = $.initActionsPopup(infosPopup);
			}
			
			objPopups.infosPopups.push(infosPopup);
		});
	};
	
	/**
	 * Initialisation des actions d'une popup
	 * $.initActionsPopup(infosPopup);
	 ******************************************/
	$.initActionsPopup = function(infos)
	{
		var btnFermer = infos.popup.find('.btn_fermer');
			
		if(!infos.popupTitre) infos.popupTitre = infos.btnPopupTexte;
		
		// Ajout du titre dynamique
		$.extend(infos.config,
		{
			title: infos.popupTitre
		});
		
		// Fusion avec la configuration spécifique
		if(infos.popupConfigSpec)
		{
			$.extend(infos.config, objPopups[infos.popupConfigSpec]);
		}
		
		// Initialisation
		infos.popup.dialog(infos.config);
		
		// Ouverture
		infos.btnPopup.click(function()
		{
			infos.popup.dialog('open');
			return false;
		});
		
		// Fermeture
		btnFermer.click(function()
		{
			infos.popup.dialog('close');
			return false;
		});
			
		return infos;
	};
	
})(jQuery);