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
		/* Configuration par défaut */
		configDefaut:
		{
			autoOpen: false,
			draggable: false,
			resizable: false,
			width: 460,
			modal: true,
			create: function(event, ui) {},
			open: function(event, ui) {},
			close: function(event, ui) { }
		},

		/* Configuration d'ajout de produit */
		configAjoutProduit:
		{
			create: function(popup)
			{
				//popup.initPopupAjoutProduit();
			},
			open: function(event, ui)
			{
				
			},
			close: function(event, ui)
			{
				$(this).trigger('fermer');
			}
		}
		
	});
	
	
	/*$(document).ready(function()
	{
		$.initPopups();
	});*/
	
	
	$(document).ready(function()
	{
		$('.btn_popup').each(function()
		{
			var btnPopup = $(this);
			btnPopup.click(function()
			{
				if(btnPopup.attr('data-popup-ajax') && btnPopup.attr('data-popup-ajax') == "true")
				{
					if($(btnPopup.attr('data-popup')).size() == 0)
					{
						btnPopup.addClass('btn_chargement');
						
						$.get(btnPopup.attr('href'), function(reponse)
						{
								$('body').append(reponse);
								$.initPopups(btnPopup);
								$(btnPopup.attr('data-popup')).dialog('open');
								btnPopup.removeClass('btn_chargement');
		
						}, 'html');
					} else {
						$(btnPopup.attr('data-popup')).dialog('open');
					}
				} else {
					if($(btnPopup.attr('data-popup')).size() == 0)
					{
						$.initPopups(btnPopup);
						
					}
					$(btnPopup.attr('data-popup')).dialog('open');
				}
				return false;
			});
		});
	});
	
	
	/**
	 * Initialisation de la fonction générique
	 * de créations de popups
	 * $.initPopups();
	 ******************************************/
	/*$.initPopups = function(btnPopup)
	{
		var btnsPopup;
		
		if(btnPopup) btnsPopup = btnPopup;
		else btnsPopup = $('.btn_popup');		
		
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
						$('body').append(reponse);
					}
					
					infosPopup.popup = $(infosPopup.popupId);
					infosPopup = $.initActionsPopup(infosPopup);

				}, 'html');
			}
			else
			{
				infosPopup = $.initActionsPopup(infosPopup);
			}
			
			objPopups.infosPopups.push(infosPopup);
		});
	};*/
	$.initPopups = function(btnPopup)
	{
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
		infosPopup = $.initActionsPopup(infosPopup);
		objPopups.infosPopups.push(infosPopup);
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

		// Création
		infos.config.create(infos.popup);
		
		// Ouverture
		/*infos.btnPopup.click(function()
		{
			infos.popup.dialog('open');
			return false;
		});*/
		
		// Fermeture
		btnFermer.live('click', function()
		{
			infos.popup.dialog('close');
			return false;
		});
			
		return infos;
	};

})(jQuery);
