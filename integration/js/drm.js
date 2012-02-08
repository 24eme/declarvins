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
	var ajouts_liquidations = $('#ajouts_liquidations');

	$(document).ready( function()
	{
		if(ajouts_liquidations) $.initAjoutsLiquidations();
	});
	
	
	/**
	 * Initialise les fonctions de l'étape
	 * Ajouts / Liquidations de la DRM
	 * $.initAjoutsLiquidations();
	 ******************************************/
	$.initAjoutsLiquidations = function()
	{
		var sections = ajouts_liquidations.find('.tableau_ajouts_liquidations');
		
		sections.each(function()
		{
			var section = $(this);
			var tabInfos = section.getInfosTableauProduits();
			
			$.afficheMasqueLigneVide(tabInfos);
			$.verifCoherenceStock(tabInfos.tableauRecapLignes);
			$.initAjoutProduit(tabInfos);
			$.stylesTableaux(tabInfos);
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
		var formRecap = blocRecapProduit.find('form');
		var tableauRecap = blocRecapProduit.find('.tableau_recap');
		var tableauRecapLignes = tableauRecap.find('tbody tr');
		var tableauRecapLigneVide = tableauRecapLignes.filter('.vide');
		var tableauRecapChamps = tableauRecap.find('input, select');
		
		var blocAjoutProduit = section.find('.ajout_produit');
		var formAjout = blocAjoutProduit.find('form');
		var tableauAjout = blocAjoutProduit.find('.tableau_ajout');
		var tableauAjoutChamps = tableauAjout.find('input, select');
		var btnAjouter = blocAjoutProduit.find('.btn_ajouter');
		var btnActions = blocAjoutProduit.find('.btn_annuler, .btn_valider');
		
		tabInfos = 
		{
			section: section,
			blocRecapProduit: blocRecapProduit,
			formRecap: formRecap,
			tableauRecap: tableauRecap,
			tableauRecapLignes: tableauRecapLignes,
			tableauRecapLigneVide: tableauRecapLigneVide,
			tableauRecapChamps: tableauRecapChamps,
			blocAjoutProduit: blocAjoutProduit,
			formAjout: formAjout,
			tableauAjout: tableauAjout,
			tableauAjoutChamps: tableauAjoutChamps,
			btnAjouter: btnAjouter,
			btnActions: btnActions
		};
		
		return tabInfos;
	};
	
	
	/**
	 * Affiche/masque la première ligne
	 * d'un tableau
	 * $.afficheMasqueLigneVide(tabInfos);
	 ******************************************/
	$.afficheMasqueLigneVide = function(t)
	{
		if(t.tableauRecapLignes.size() > 1) t.tableauRecapLigneVide.hide();
		else t.tableauRecapLigneVide.show();
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
			
			if(disponible.val() > 0) stockVide.attr('disabled', 'disabled');
		});
	};
			
	
	
	/**
	 * Initialise les actions pour ajouter un
	 * produit à un tableau
	 * $.initAjoutProduit(tabInfos);
	 ******************************************/
	$.initAjoutProduit = function(t)
	{
		var disponible = t.tableauAjout.find('.disponible input');
		var stockVide = t.tableauAjout.find('.stock_vide input');
		
		t.btnActions.hide();
		t.tableauAjout.hide();
		
		disponible.saisieNum(true, function()
		{
			if(disponible.val() > 0) stockVide.attr('disabled','disabled');
			else stockVide.removeAttr('disabled');
			
			stockVide.removeAttr('checked');
		});
		
		// Bouton ajouter un produit
		t.btnAjouter.click(function()
		{
			t.btnAjouter.hide();
			t.btnActions.show();
			t.tableauAjout.show();
			
			// Desactivation des champs du tableau récapitulatif
			t.tableauRecapChamps.attr('disabled', 'disabled');
			t.tableauAjoutChamps.removeAttr('disabled');
			
			return false;
		});
		
		// Boutons de validation et d'annulation
		// d'ajout de produit
		t.btnActions.click(function()
		{
			var btn = $(this);
			var donnees;
			
			// Validation
			if(btn.hasClass('btn_valider'))
			{
				donnees = t.tableauAjoutChamps.serializeArray();
				
				t.tableauAjoutChamps.attr('disabled', 'disabled');
				t.btnActions.attr('disabled', 'disabled');
				
				$.post(t.formAjout.attr('action'), donnees, function(html)
				{
					//$.ajouterLigneTableau(html);
					var nouvelleLigne = $(html);
					t = $.insertionNouveauProduit(t, nouvelleLigne);
					
					$.finAjoutProduit(t, function()
					{
						$.verifCoherenceStock(t.tableauRecapLignes);
					});
				});	
			}
			
			// Annulation
			else if(btn.hasClass('btn_annuler'))
			{
				$.finAjoutProduit(t);
			}
			return false;
		});
	};
	
	/**
	 * Fonction de fin d'ajout de produit
	 * $.finAjoutProduit(tabInfos, callback);
	 ******************************************/
	$.finAjoutProduit = function(t, callback)
	{
		t.tableauAjoutChamps.val('');
		t.tableauAjoutChamps.removeAttr('checked');
		t.btnActions.removeAttr('disabled');
		
		t.btnAjouter.show();
		t.btnActions.hide();
		t.tableauAjout.hide();
		
		// Activation des champs du tableau récapitulatif
		t.tableauRecapChamps.removeAttr('disabled');
		t.tableauAjoutChamps.attr('disabled', 'disabled');
		
		if(callback) callback();
	}
	
	
	/**
	 * Insertion d'une nouvelle ligne dans le
	 * tableau récaptitulatif
	 * $.insertionNouveauProduit(tabInfos, nouvelleLigne);
	 ******************************************/
	$.insertionNouveauProduit = function(t, nouvelleLigne)
	{
		var majT;
		
		nouvelleLigne.insertAfter(t.tableauRecapLignes.last());
		majT =  $(t.section).getInfosTableauProduits();
		$.stylesTableaux(majT);
		
		return majT;
	};
	
	/**
	 * Styles des tableaux
	 * $.stylesTableaux(tabInfos);
	 ******************************************/
	$.stylesTableaux = function(t)
	{
		var lignes = t.tableauRecapLignes.not('.vide');
		var casesTableauRecap = t.tableauRecapLignes.filter(':last').children('td');
		var casesTableauAjout = t.tableauAjout.find('tr td');
		
		// Alternance de couleurs
		lignes.removeClass('alt');
		lignes.filter(':odd').addClass('alt');
		
		/* Egalise les cases du tableau récapitualif avec celle du tableau d'ajout */
		casesTableauRecap.each(function(i)
		{
			var largeur = $(this).width();
			var td = casesTableauAjout.eq(i);
			td.width(largeur);
			td.children().css('max-width', largeur);
		});
	};

})(jQuery);