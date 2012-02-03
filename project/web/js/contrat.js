/**
 * Fichier : contrat.js
 * Description : fonctions JS spécifiques à la gestion d'un contrat
 * Auteur : Hamza Iqbal - hiqbal[at]actualys.com
 * Copyright: Actualys
 ******************************************/

/**
 * Variables globales
 ******************************************/
objContrat = {};

/**
 * Initialisation
 ******************************************/
(function($)
{
	$(document).ready( function()
	{
		if($('#infos_etablissements').exists())
		{
			$.initInfosEtablissements();
		}
		
		if($('#contratetablissement_famille').exists())
		{
			$.initEtablissementFamille();
		}
		
		if($('#adresse_comptabilite').exists())
		{
			$.toggleAdresseCompatibilite();
		}
	});

	/**
	 * Initialisation des informations 
	 * des établissements
	 * $.initInfosEtablissements();
	 ******************************************/
	$.initInfosEtablissements = function()
	{
		var infosEtablissements = $('#infos_etablissements');
		var champNbEtablissements = $('#contrat_nb_etablissement');
		var templateEtablissement = $('#template_etablissement');
		var btnAjoutEtablissement = $('#btn_ajouter_etablissement');
		var etablissements = infosEtablissements.find('.etablissement');
		var nbEtablissements = etablissements.size();
		var premierEtablissement = etablissements.filter(':first');
		var dernierEtablissement = etablissements.filter(':last');

		$.extend(objContrat,
		{
			infosEtablissements: infosEtablissements,
			nbEtablissements: nbEtablissements,
			champNbEtablissements: champNbEtablissements,
			btnAjoutEtablissement: btnAjoutEtablissement,
			templateEtablissement : templateEtablissement,
			etablissements: etablissements,
			premierEtablissement: premierEtablissement,
			dernierEtablissement: dernierEtablissement
		});
		
		etablissements.not(premierEtablissement).supprimerEtablissement();
		
		// Bouton d'ajout
		$.ajouterEtablissement();
	};
	
	/**
	 * Mise à jour du dernier établissement
	 * et du nombre d'établissements
	 * $.majNbEtablissements();
	 ******************************************/
	$.majNbEtablissements = function(operation)
	{
		objContrat.etablissements = objContrat.infosEtablissements.find('.etablissement');
		objContrat.dernierEtablissement = objContrat.etablissements.filter(':last');
		objContrat.nbEtablissements = objContrat.etablissements.size();
		
		objContrat.champNbEtablissements.val(objContrat.nbEtablissements);
	};
	
	
	/**
	 * Ajout d'un établissement
	 * $.ajouterEtablissement();
	 ******************************************/
	$.ajouterEtablissement = function()
	{
		objContrat.btnAjoutEtablissement.click(function()
		{
			// Récupération du template et insertion
			objContrat.templateEtablissement.tmpl({nbEtablissements: objContrat.nbEtablissements}).appendTo(objContrat.infosEtablissements);
			$.majNbEtablissements('ajout');
			
			// Suppression de l'établissement
			objContrat.dernierEtablissement.supprimerEtablissement();
			
			return false;
		});
	};
	
	
	/**
	 * Suppression d'un établissement
	 * $(etablissement).supprimerEtablissement();
	 ******************************************/
	$.fn.supprimerEtablissement = function()
	{
		var etablissements = $(this);
		
		etablissements.each(function()
		{
			var etablissement = $(this);
			var btnSupprimer = etablissement.find('a.supprimer');
		
			btnSupprimer.click(function()
			{
				// Suppression
				etablissement.remove();
				$.majNbEtablissements('suppression');
				
				return false;
			});
		});
	};
	
	
	/**
	 * Suppression de tous les établissements
	 * $.supprimerTousEtablissements();
	 ******************************************/
	$.supprimerTousEtablissements = function()
	{
		objContrat.etablissements.not(':first').remove();
		$.majNbEtablissements('suppressionTous');
	};
	
	
	
	/**
	 * Initialisation de la famille de 
	 * l'établissement
	 * $.initEtablissementFamille();
	 ******************************************/
	$.initEtablissementFamille = function()
	{
		if(familles)
		{
			var famillesJSON = JSON.parse(familles);
			var champFamilles = $("#contratetablissement_famille");
			var champFamillesVal = champFamilles.val();
			var champSousFamilles = $("#contratetablissement_sous_famille");
			var templateSousFamilles = $('#template_options_sous_famille');
			
			// Création de l'objet
			$.extend(objContrat,
			{
				famillesJSON: famillesJSON,
				champFamilles: champFamilles,
				champFamillesVal: champFamillesVal,
				champSousFamilles: champSousFamilles,
				champSousFamillesVal: '',
				tabSousFamilles: [],
				templateSousFamilles: templateSousFamilles
			});
			
			
			// Si le champ est prérempli
			if(champFamillesVal)
			{
				$.majEtablissementSousFamille();
			}
			
			
			// A chaque sélection de familles
			objContrat.champFamilles.change(function()
			{
				objContrat.champFamillesVal = objContrat.champFamilles.val();
				$.majEtablissementSousFamille();
			});
		}
	};
	
	
	/**
	 * Met à jour la sous-famille de 
	 * l'établissement
	 * $.majEtablissementSousFamille();
	 ******************************************/
	$.majEtablissementSousFamille = function()
	{
		var objTemplate = {};
		
		objContrat.champSousFamillesVal = objContrat.champSousFamilles.val();
		objContrat.tabSousFamilles = objContrat.famillesJSON[objContrat.champFamillesVal];
		objContrat.champSousFamilles.html('');
		
		// Parcours des sous-familles
		for(var i in objContrat.tabSousFamilles)
		{
			objTemplate = { value: objContrat.tabSousFamilles[i] };
			
			// Si l'éléments courant doit être sélectionné
			if(objContrat.champSousFamillesVal == objContrat.tabSousFamilles[i])
			{
				$.extend(objTemplate, {selected: true });
			}
			
			// Insertion de l'option
			objContrat.templateSousFamilles.tmpl(objTemplate).appendTo(objContrat.champSousFamilles);
		}
	};
	
	
	/**
	 * Affiche / masque l'adresse de
	 * la comptabilité
	 * $.toggleAdresseCompatibilite();
	 ******************************************/
	$.toggleAdresseCompatibilite = function()
	{
		var blocAdresseComptabilite = $('#adresse_comptabilite');
		var radios = $('input[name=adresse_comptabilite]');
		var val = radios.filter(':checked').val();
		
		if(val == "Oui") blocAdresseComptabilite.show();
		else blocAdresseComptabilite.hide();
		
		radios.click(function()
		{
			val = radios.filter(':checked').val();
			
			if(val == "Oui") blocAdresseComptabilite.show();
			else blocAdresseComptabilite.hide();
		});
	};
	
})(jQuery);