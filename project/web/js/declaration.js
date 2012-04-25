/**
 * Fichier : déclaration.js
 * Description : fonctions JS spécifiques à la déclaration
 * Auteur : Hamza Iqbal - hiqbal[at]actualys.com
 * Copyright: Actualys
 ******************************************/

/**
 * Initialisation
 ******************************************/
(function($)
{
	// Variables globales 
	var colonnesDR = $('#colonnes_dr');
	var colIntitules = $('#colonne_intitules');
	var colSaisies = $('#col_saisies');
	var colSaisiesCont = $('#col_saisies_cont');
	var colSaisiesRecolte = colSaisiesCont.find('.col_recolte');
	var colTotal = $('#colonne_total');
	
	var colActive;
	var colEditee;
	
	var colFocus;
	var colFocusNum = 1; // Colonne qui a le focus par défaut
	
	var btnAjouter = colonnesDR.find('.btn_ajouter');
	
	
	$(document).ready( function()
	{
		$.initColonnes();
		$.verifierChampsNombre();
		$.calculerSommesChamps();
		$.toggleGroupesChamps();
	});
	
	/**
	 * Calcul dynamique des dimmensions des colonnes
	 * $.initColonnes();
	 ******************************************/
	$.initColonnes = function()
	{
		var colEgales = colIntitules.add(colSaisiesCont).add(colTotal);
		var colSaisiesActive = colSaisiesRecolte.filter('.col_active');
		var largeurCSC = 0;
		
		
		// Ajout du masque
		colSaisiesRecolte.append('<div class="col_masque"></div>');
		
		// Calcul de la largeur du conteneur des colonnes de saisies
		colSaisiesRecolte.each(function()
		{
			largeurCSC += $(this).outerWidth(true);
		});
		
		colSaisiesCont.width(largeurCSC);
	
		// Initialisation des actions des boutons des colonnes
		$.initColBoutons();
		
		// Initialisation des actions associées aux raccourci clavier
		$.initRaccourcis();
		
		// Initialisation le comportement des champs au focus et à la saisie
		$.initComportementsChamps();
		
		// Positionnement du focus par défaut
		$.initColFocus();
		
		// Egalisation de la hauteur des colonnes et des titres
		colEgales.find('.denomination, h2').hauteurEgale();
	};
	
	
	/**
	 * Positionne le scroll en fonction de la
	 * colonne avec le focus / activée
	 * $.majColSaisiesScroll();
	 ******************************************/
	$.majColSaisiesScroll = function()
	{
		if(colActive && colActive.size() > 0) colSaisies.scrollTo(colActive, 200);
		else if(colFocus.size() > 0) colSaisies.scrollTo(colFocus, 200);
		else colSaisies.scrollTo({top: 0, left: 0}, 200);
		
		/*if(ancre != '' && $(ancre)) colSaisiesActive = $(ancre);
		if(colSaisiesActive.size() > 0) colSaisies.scrollTo(colSaisiesActive, 0);
		else colSaisies.scrollTo({top: 0, left: largeurCSC}, 0);*/
	};
	
	/**
	 * Initialisation des actions des boutons des colonnes
	 * $.initColBoutons();
	 ******************************************/
	$.initColBoutons = function()
	{
		colSaisiesRecolte.each(function()
		{
			var col = $(this);
			var boutons = col.find('.col_btn button');
			var btnModifier = boutons.filter('.btn_modifier');
			var btnSupprimer = boutons.filter('.btn_supprimer');
			var btnReinitialiser = boutons.filter('.btn_reinitialiser');
			var btnValider = boutons.filter('.btn_valider');
			
			
			// Modification de la colonne
			btnModifier.click(function()
			{
				if(!colActive) col.majColActive(true);
				return false;
			});
			
			// Suppression d'une colonne
			btnSupprimer.click(function()
			{
				return col.supprimerCol();
			});
			
			// Réinitialisation des valeurs d'une colonne
			btnReinitialiser.click(function()
			{
				$.reinitialiserCol();
				return false;
			});
			
			// Validation des valeurs d'une colonne
			btnValider.click(function()
			{
				$.validerCol();
				return false;
			});
		});
	};
	
	
	/**
	 * Initialisation des actions associées 
	 * aux raccourci clavier
	 * $.initRaccourcis();
	 ******************************************/
	$.initRaccourcis = function(col)
	{
		// Ctrl + flèche gauche ==> Changement de focus
		$.ctrl(37, function() { $.majColFocus('prec'); });
		
		// Ctrl + flèche droite ==> Changement de focus
		$.ctrl(39, function() { $.majColFocus('suiv'); });
		
		// Ctrl + M ==> Commencer édition colonne avec focus
		$.ctrl(77, function () { colFocus.majColActive(true); });
		
		// Ctrl + touche supprimer ==> Suppression colonne avec focus
		$.ctrl(46, function() { colFocus.find('.btn_supprimer').trigger('click'); });
		
		// Ctrl + Z ==> Réinitialisation colonne active
		$.ctrl(90, function() { colFocus.find('.btn_reinitialiser').trigger('click'); });
		
		// Ctrl + Entrée ==> Validation de la colonne active
		$.ctrl(13, function() { colFocus.find('.btn_valider').trigger('click'); });
	};
		
	/**
	 * Supprimer la colonne demandée
	 * $.fn.supprimerCol();
	 ******************************************/
	$.fn.supprimerCol = function()
	{
		// S'il n'y a pas de colonne active définie
		if(!colActive)
		{
			var confirmSupprimer = confirm('Confirmez-vous la supression de cette colonne');
			
			if(confirmSupprimer)
			{
				alert('suppression...');
				return true;
			}
			else return false;
		}
		else
		{
			alert('Veuillez valider ou réinitialiser cette colonne pour la supprimer');
			return false;
		}
	};
	
	/**
	 * Réinitialise les valeurs de la colonne
	 * $.reinitialiserCol();
	 ******************************************/
	$.reinitialiserCol = function()
	{
		// S'il y a une colonne active définie
		if(colActive)
		{
			var champs = colActive.find('input:text, select');
			
			champs.each(function()
			{
				var champ = $(this);
				var valDefaut = champ.attr('data-val-defaut');
				
				if(champ.is('input'))
				{
					champ.val(valDefaut);
				}
				else if(champ.is('select'))
				{
					champ.children().removeAttr('selected');
					champ.children('[value='+valDefaut+']').attr('selected', 'selected');
				}
			});
			
			$.enleverColActive();
		}
	};
	
	/**
	 * Valide les valeurs de la colonne en cours
	 * et les envoie en AJAX
	 * $.validerCol();
	 ******************************************/
	$.validerCol = function()
	{
		// S'il y a une colonne active définie
		if(colActive)
		{
			var form = colActive.find('form');
			var champs = colActive.find('input:text, select');
			var donneesCol = form.serializeArray();
			
			colActive.addClass('col_envoi');
			/*var btn = colActive.find('.col_btn button');
			
			btn.css('visibility', 'hidden');
			*/
			champs.each(function()
			{
				var champ = $(this);
				var val = champ.val();
				
				champ.attr('data-val-defaut', val);
			});
				
			$.post(form.attr('action'), donneesCol, function (data)
			{
				$('#saving_notification').hide();
				if(!data.success) { $('#error_notification').show(); }
				$('#forms_errors').html(data.content);
				
				//btn.css('visibility', 'visible');
				colActive.removeClass('col_envoi');
				$.enleverColActive();
			}, "json");
			
			$('#error_notification').hide();
			$('#saving_notification').show();
		}
	};
	
	
	/**
	 * Initialise le comportement des champs 
	 * au focus et à la saisie
	 * $.initComportementsChamps();
	 ******************************************/
	$.initComportementsChamps = function()
	{
		colSaisiesRecolte.each(function()
		{
			var colonne = $(this);
			var champs = colonne.find('input:text, select');
			
			champs.each(function()
			{
				var champ = $(this);
				
				var valDefaut = champ.attr('data-val-defaut');
				
				// Focus sur la colonne courante s'il n'y pas de colonne active
				// et si la colonne courante n'a pas déjà le focus
				champ.focus(function()
				{
					if(!colActive && !colonne.hasClass('col_focus')) $.majColFocus(colonne);
				});
				
			
				if(champ.is('select'))
				{
					champ.blur(function()
					{
						var val = champ.val();
						if(!colActive && val != valDefaut) { colEditee = colonne; colonne.majColActive(false); }
					});
					
					// Si la valeur du champ change alors la colonne est activée
					champ.change(function()
					{
						if(!colActive) colonne.majColActive(false);
					});
				}
			});
		});
	};
	
	
	/**
	 * Initialise et gère le focus sur les colonnes
	 * $.initColFocus();
	 ******************************************/
	$.initColFocus = function()
	{
		var colCurseurs = colSaisiesRecolte.find('a.col_curseur');
		
		/*if(colFocusDefaut) colFocusNum = colFocusDefaut;
		else*/ colFocusNum = colCurseurs.first().attr('data-curseur');
		
		// Colonne au focus par défaut
		colFocus = $('#col_recolte_'+colFocusNum);
		colFocus.addClass('col_focus');
		colCurseur = colFocus.find('a.col_curseur');
		
		colCurseur.focus();
		
		// Positionnement du scroll
		$.majColSaisiesScroll();
	};
	
	/**
	 * Change le focus sur les colonnes
	 * $.majColFocus(objet);
	 ******************************************/
	$.majColFocus = function(objet)
	{
		var colCurseur;
		var direction = false;
		
		$(':focus').blur();
		
		// S'il n'y a pas de colonne active définie
		if(!colActive && !colEditee)
		{
			// Si c'est une direction
			if(typeof(objet) == "string") direction = objet;
			
			colFocus.removeClass('col_focus');
			
			if(direction)
			{
				// Colonne précédente
				if(direction == 'prec')
				{
					if(colFocus.prev().size() > 0) colFocus = colFocus.prev();
					else colFocus = colSaisiesRecolte.last();
				}
				// Colonne suivant
				else
				{
					if(colFocus.next().size() > 0) colFocus = colFocus.next();
					else colFocus = colSaisiesRecolte.first();
				}
			}
			else { colFocus = objet; }
			
			colFocus.addClass('col_focus');
			colCurseur = colFocus.find('a.col_curseur');
			colFocusNum = colCurseur.attr('data-curseur');
			
			if(direction) colCurseur.focus();
			
			$.majColSaisiesScroll();
		}
	};
	
	
	/**
	 * Initialise l'activation d'un colonne
	 ******************************************/
	$.initColActive = function()
	{
		
	};
	
	/**
	 * Active une colonne
	 * $(col).majColFocus();
	 ******************************************/
	$.fn.majColActive = function(focusCurseur)
	{
		colActive = $(this);
		colFocus.removeClass('col_focus');
		colActive.addClass('col_active').addClass('col_focus');
		colFocus = colActive;
		colCurseur = colFocus.find('a.col_curseur');
		colFocusNum = colCurseur.attr('data-curseur');
		if(focusCurseur) colCurseur.focus();
		
		colActive.desactiverAutresCol();
		
		$.majColSaisiesScroll();
	};
	
	
	/**
	 * Désactive toutes les colonnes sauf celle que l'on édite
	 * $(col).desactiverAutresCol();
	 ******************************************/
	$.fn.desactiverAutresCol = function()
	{
		var colCourante = $(this);
		var colAutres = colSaisiesRecolte.not(colCourante);
		var champsADesactiver = colAutres.find('input, select')
		
		// désactivation des champs
		colAutres.addClass('col_inactive');
		champsADesactiver.attr('disabled', 'disabled');
	};
	
	
	/**
	 * Supprime le statut de la colonne active
	 * et réactive tous les champs
	 * $.enleverColActive();
	 ******************************************/
	$.enleverColActive = function()
	{		
		if(colActive)
		{
			colActive.removeClass('col_active');
			colCurseur.focus();
			colActive = false;
			colEditee = false;
			
			// réactivation des champs
			colSaisiesRecolte.removeClass('col_inactive');
			colSaisiesRecolte.find('input, select').removeAttr('disabled');
		}
	};
	
	
	/**
	 * Met à jour les hauteurs des masques
	 * $.majHauteurMasque();
	 ******************************************/
	$.majHauteurMasque = function()
	{
		colSaisiesRecolte.each(function()
		{
			var col = $(this);
			var colCont = col.find('.col_cont');
			
			var paddingCol = parseInt(colCont.css('padding-bottom'));
			var btn =  parseInt(col.find('.col_btn').height());
			var hauteur =  parseInt(col.height()) - paddingCol - btn;
			
			var hauteur =  parseInt(col.height());
			col.find('.col_masque').height(hauteur);
		});
	};
	
	/**
	 * Calcul des sommes des champs
	 * $.verifierChampsNombre();
	 ******************************************/
	$.verifierChampsNombre = function()
	{
		var champs = colSaisiesRecolte.find('input.num');
	
		champs.each(function()
		{
			var champ = $(this);
			var colonne = champ.parents('.col_recolte');
			var float = champ.hasClass('num_float');
			
			// A chaque touche pressée
			champ.keypress(function(e)
			{	
				var val = $(this).val();
				var touche = e.which;
				var ponctuationPresente = (val.indexOf('.') != -1 || val.indexOf(',') != -1);
				var chiffre = (touche >= 48 && touche <= 57); // Si chiffre
				
				// touche "entrer"
				if(touche == 13) return e;
				
				// Champ nombre décimal
				if(float)
				{ 
					// !backspace && !null && !point && !virgule && !chiffre
					if(touche != 8 && touche != 0 && touche != 46 && touche != 44 && !chiffre) return false;  	
					// point déjà présent
					if(touche == 46 && ponctuationPresente) return false; 
					// virgule déjà présente
					if(touche == 44 && ponctuationPresente) return false; 
					// 2 décimales
					if(val.match(/[\.\,][0-9][0-9]/) && chiffre && e.currentTarget && e.currentTarget.selectionStart > val.length - 3) return false;
				}
				// Champ nombre entier
				else
				{
					if(touche != 8 && touche != 0 && !chiffre) return false;
				}
				
				colonne.majColActive(false);
				return e;
			});
			
			// A chaque fois que l'on quitte le champ
			champ.blur(function()
			{
				$(this).nettoyageChamps();
				$.calculerSommesChamps();
			});
		});
	};
	
	
	/**
	 * Nettoie les champs après la saisie
	 * $(champ).nettoyageChamps();
	 ******************************************/
	$.fn.nettoyageChamps = function()
	{
		var champ = $(this);
		var val = champ.attr('value');
		var float = champ.hasClass('num_float');
		
		// Si quelque chose a été saisi
		if(val)
		{
			// Remplacement de toutes les virgules par des points
			if(val.indexOf(',') != -1) val = val.replace(',', '.');
			
			// Si un point a été saisi sans chiffre
			if(val.indexOf('.') != -1 && val.length == 1) val = '0';
			
			// Un nombre commençant par 0 peut être interprété comme étant en octal
			if(val.indexOf('0') == 0 && val.length > 1) val = val.substring(1);
			
			// Comparaison nombre entier / flottant
			if(parseInt(val) == parseFloat(val) || !float) val = parseInt(val);
			else val = parseFloat(val).toFixed(2);
		}
		// Si rien n'a été saisi
		else val = 0;
		
		// Si ce n'est pas un nombre (ex : copier/coller d'un texte)
		if(isNaN(val)) val = 0;
		
		champ.attr('value', val);
	};
	
	
	/**
	 * Calcul des sommes des champs
	 * $.calculerSommesChamps();
	 ******************************************/
	$.calculerSommesChamps = function()
	{
		// Parcours des colonnes
		colSaisiesRecolte.each(function()
		{
			var colonne = $(this);
			var champsSomme = colonne.find('input.somme, input.somme_groupe, input.somme_stock_fin');
			var champSommeStockDebut = colonne.find('input.somme_stock_debut');
			var champSommeEntrees = colonne.find('input.somme_entrees');
			var champSommeSorties = colonne.find('input.somme_sorties');
			
			// Parcours des champs sommes
			champsSomme.each(function()
			{
				var champSomme = $(this);
				var tabChamps;
				var champsAddition;
				var float = champSomme.hasClass('num_float');
				var somme = 0;
				var valeur = 0;
				
				// Récupération des champs à additionner
				if(champSomme.hasClass('somme_groupe') || champSomme.hasClass('somme'))
				{
					// Sommes des groupes
					if(champSomme.hasClass('somme_groupe'))
					{
						champsAddition = champSomme.parents('.groupe').find('ul li input');
					}
					// Sommes simples
					else
					{
						tabChamps = champSomme.attr('data-champs-somme').split(';');
						
						for(var i = 0; i < tabChamps.length; i++)
						{
							champsAddition.add('#' + tabChamps[i])
						}
					}
					
					// Addition des champs concernés
					champsAddition.each(function()
					{
						valeur = $(this).attr('value');
						if (valeur == '') valeur = 0;
		
						if(float) somme += parseFloat(valeur);
						else somme += parseInt(valeur);
					});
				}
				else if(champSomme.hasClass('somme_stock_fin'))
				{
					// Ajout du stock théorique du début
					if(champSommeStockDebut.hasClass('num_float')) somme += parseFloat(champSommeStockDebut.val());
					else somme += parseInt(champSommeStockDebut.val());
					
					// Ajout des entrées
					if(champSommeEntrees.hasClass('num_float')) somme += parseFloat(champSommeEntrees.val());
					else somme += parseInt(champSommeEntrees.val());
					
					// Soustraction des sorties
					if(champSommeSorties.hasClass('num_float')) somme -= parseFloat(champSommeSorties.val());
					else somme -= parseInt(champSommeSorties.val());
				
					if(float) somme = parseFloat(somme);
					else somme = parseInt(somme);
				}
				
				if(float) somme = somme.toFixed(2); // Arrondi à 2 chiffres après la virgule
				champSomme.attr('value', somme);
			});
		});
	};
	
	/**
	 * Affiche/Masque les groupes de champs
	 * $.toggleGroupesChamps();
	 ******************************************/
	$.toggleGroupesChamps = function()
	{
		var groupesIntitules = colIntitules.find('.groupe');
		
		groupesIntitules.each(function()
		{
			var groupe = $(this);
			var gpeId = groupe.attr('data-groupe-id');	
			var titre = groupe.children('p');
			var listeIntitules = groupe.children('ul');
			var intitules = listeIntitules.children();
			
			var gpeAssocie = colSaisies.find('.groupe[data-groupe-id='+gpeId+']');
			var gpeAssocieIntitules = gpeAssocie.children('p');
			var gpeChamps = gpeAssocie.children('ul');

			// Egalisation des intitulés
			titre.add(gpeAssocieIntitules).hauteurEgale();
			
			intitules.each(function(i)
			{
				var intitule = $(this);
				var gpeChampsItems = gpeChamps.find('li:eq('+i+')');
			
				intitule.add(gpeChampsItems).hauteurEgale();
			});
			
			// Masque les groupes
			listeIntitules.hide();
			gpeChamps.hide();
			//$.majHauteurMasque();
			
			// Affiche / Masque les groupes et les champs associés
			titre.click(function()
			{
				groupe.toggleClass('groupe_ouvert');
				listeIntitules.slideToggle();
				gpeChamps.slideToggle();
				//$.majHauteurMasque();
			});
			
			gpeAssocieIntitules.find('input').focus(function()
			{
				var champ = $(this);
				var champSuivant = champ.parents('.groupe').find('ul input:first');
				
				if(!groupe.hasClass('groupe_ouvert'))
				{
					groupe.addClass('groupe_ouvert');
					listeIntitules.slideToggle();
					gpeChamps.slideToggle();
					//$.majHauteurMasque();
					
					// focus on the next input if the current input is not editable 
					if(champ.attr('readonly')) champSuivant.focus();
				}
			});
		});
	};
	
})(jQuery);