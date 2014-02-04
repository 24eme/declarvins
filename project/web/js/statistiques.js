/**
 * Fichier : statistiques.js
 * Description : fonctions JS spécifiques aux statistiques
 * Auteur : Mikaël Guillin - mguillin[at]actualys.com
 * Copyright: Actualys
 ******************************************/

(function($)
{
	
	var statsConteneur = $('#contenu.statistiques');
	
	/**
	* Navigation des options de Recherche
	******************************************/
	var optionsRecherche = function()
	{	
		var popupForm = statsConteneur.find('#form_ajout'),
			btnOptions = $('#barre_sub_navigation .options_recherche a'),
			menuNiv1 = statsConteneur.find('nav.options_recherche'),
			btnsNiv1 = menuNiv1.find('> ul > li > a'),	
			menusNiv2 = menuNiv1.find('ul li ul'),
			btnsNiv2 = menusNiv2.find('a');
		
		// Premier niveau du menu
		btnsNiv1.each(function()
		{
			var btn = $(this),
				menuNiv2 = btn.next();
				
			btn.click(function(e)
			{
				e.preventDefault();
				e.stopPropagation();
				
				// On fait disparaitre tous les sous menu sauf le courant
				menusNiv2.not(menuNiv2).removeClass('visible');
				menuNiv2.toggleClass('visible');
			});
		});
		
		// Deuxième niveau du menu
		btnsNiv2.each(function()
		{
			var btn = $(this),
				idFiltre = btn.attr('data-filtre'),
				$idFiltre = $(idFiltre);
			
			btn.click(function()
			{
				popupForm.find('fieldset').removeClass('visible');
				$idFiltre.addClass('visible');
			});
			
		});
		
		btnsNiv2.fancybox($.extend({}, fbConfig,
		{
			padding: 20,
			wrapCSS: 'fb-statistiques',
			autoSize: false,
			height: 'auto',
			width: 500,
			scrolling: 'visible'
		}));
		
		// A chaque fois qu'on ajoute un champ on agrandit la fancybox
		$('.btn_ajouter_ligne_template').click(function()
		{
			$.fancybox.update();
		});
		
		// On cache les sous menus lorsqu'on clique en dehors
		$(document).click(function()
		{
			menusNiv2.removeClass('visible');
		});
	};
	
	/**
	* Initialisation des date picker
	******************************************/
	var initDatePicker = function()
	{	
		var datePickerDebut = statsConteneur.find('.calendrier_debut .date_picker'),
			datePickerFin = statsConteneur.find('.calendrier_fin .date_picker'),
			
			majSelect = function(date, obj, indexDebut)
			{
				var elemsSelect = $('#'+obj.id).parents('.calendriers').prev().find('select'),
					elemsDate = date.split('/'),
					iDate = 0,
					limite = indexDebut + 3;
					
				// On commence par le premier select
				for(var i = indexDebut; i < limite; i++)
				{
					elemsSelect.eq(i).find('option:contains("'+elemsDate[iDate]+'")').attr('selected', 'selected');
					iDate++;
				}
			};
		
			datePickerDebut.datepicker($.extend({}, dpConfig,
			{
				onSelect: function(date, obj)
				{	
					majSelect(date, obj, 0);
				}
			}));
			
			datePickerFin.datepicker($.extend({}, dpConfig,
			{
				onSelect: function(date, obj)
				{
					majSelect(date, obj, 3);
				}
			}));		
	};
	
	$(document).ready(function()
	{
		if($('#barre_sub_navigation .options_recherche').exists()) { optionsRecherche(); }
		
		if(statsConteneur.find('.date_picker').exists()) { initDatePicker(); }
		
		statsConteneur.find("#filtre_produits_items select").combobox();
	});
})(jQuery);