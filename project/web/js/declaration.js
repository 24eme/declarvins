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
	var anchorIds = {"entrees" : 2, "sorties" : 3}
	// Variables globales 
	var colonnesDR = $('#colonnes_dr');
	var colIntitules = $('#colonne_intitules');
	var colSaisies = $('#col_saisies');
	var colSaisiesCont = $('#col_saisies_cont');
	var colSaisiesRecolte = colSaisiesCont.find('.col_recolte');
	var colTotal = $('#colonne_total');
	
	var colActiveDefaut = colSaisiesRecolte.filter('.col_active');
	var colActive;
	var colEditee;
	
	var colFocus;
	var colFocusNum = 1; // Colonne qui a le focus par défaut
	
	var btnAjouter = colonnesDR.find('.btn_ajouter');
	
	var btnEtapesDR = $('#btn_etape_dr');
	var btnPrecSuivProd = $('#btn_suiv_prec');

	var masqueColActive;
	
	$(document).ready( function()
	{
		if(colonnesDR.exists())
		{
			$.initColonnes();
			$.verifierChampsNombre();
			$.calculerSommesChamps();
			$.calculerChampsInterdependants();
			$.toggleGroupesChamps();
			$.initChoixRadio();
		}
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
		colEgales.find('.couleur, h2').hauteurEgale();
		colEgales.find('.label').hauteurEgale();
		
		$.initMasqueColActive();
		
		// Colonne active par défaut
		if(colActiveDefaut.exists()) colActiveDefaut.majColActive();
	};
	
	/**
	 * Initialise le masque qui désactive les 
	 * liens lorque une colonne est active
	 * $.initMasqueColActive();
	 ******************************************/
	$.initMasqueColActive = function()
	{
		var hauteur = $('#contenu_onglet').position().top;
		masqueColActive = $('<div id="masque_col_active"></div>').height(hauteur).hide();
		
		$('#contenu').append(masqueColActive);
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
			//var btnModifier = boutons.filter('.btn_modifier');
			//var btnSupprimer = boutons.filter('.btn_supprimer');
			var btnReinitialiser = boutons.filter('.btn_reinitialiser');
			var btnValider = boutons.filter('.btn_valider');
			
			
			// Modification de la colonne
			/*btnModifier.click(function()
			{
				if(!colActive) col.majColActive(true);
				return false;
			});*/
			
			// Suppression d'une colonne
			/*btnSupprimer.click(function()
			{
				return col.supprimerCol();
			});*/
			
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
		//$.ctrl(46, function() { colFocus.find('.btn_supprimer').trigger('click'); });
		
		// Ctrl + Z ==> Réinitialisation colonne active
		$.echap(function() { colFocus.find('.btn_reinitialiser').trigger('click'); });
		
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
			$.calculerSommesChamps();
			$.calculerChampsInterdependants();
			
			
			var form = colActive.find('form');
			var donneesCol = form.serializeArray();
			
			colActive.addClass('col_envoi');
			/*var btn = colActive.find('.col_btn button');
			
			btn.css('visibility', 'hidden');
			*/
		
			
			$.post(form.attr('action'), donneesCol, function (data)
			{
				if(data.success)
				{
					var champs = colActive.find('input:text, select');
					
					champs.each(function()
					{
						var champ = $(this);
						var val = champ.val();
						var val_defaut = champ.attr('data-val-defaut');

						if (val_defaut != val) {
							if (colActive.attr('data-cssclass-rectif')) {
								champ.parent().addClass(colActive.attr('data-cssclass-rectif'));
							}
						}
						
						champ.attr('data-val-defaut', val);
					});

					var cond = /^drm_detail\[(entrees|sorties)\]/;
					var totalCol = 0;
					for (var i in donneesCol) {
						if ((donneesCol[i].name).match(cond) && !isNaN(donneesCol[i].value) && donneesCol[i].value) {
							totalCol += parseFloat(donneesCol[i].value);
						}
					}
					if (totalCol > 0) {
						var appellation_produit_saisie = parseInt($('#onglets_principal li.actif .appellation_produit_saisie').text());
						var appellation_produit_total = parseInt($('#onglets_principal li.actif .appellation_produit_total').text());
						if (appellation_produit_saisie < appellation_produit_total) {
							$('#onglets_principal li.actif .appellation_produit_saisie').text(appellation_produit_saisie + 1);
							appellation_produit_saisie++;
						}
						if (appellation_produit_saisie == appellation_produit_total) {
							$('#onglets_principal li.actif .completion').addClass('completion_validee');
						}
					}
					
					// Rétablissement des groupe ouverts / fermés par défaut
					colIntitules.find('.groupe').each(function()
					{
						var groupe = $(this);
						
						if(groupe.hasClass('groupe_ouvert') && !groupe.hasClass('bloque')) groupe.trigger('fermer');

						if(groupe.hasClass('demarrage-ouvert') && !groupe.hasClass('bloque')) groupe.trigger('ouvrir');
					});
				}
				
				$('#forms_errors').html(data.content);
				
				//btn.css('visibility', 'visible');
				colActive.removeClass('col_envoi');
				$.enleverColActive();
			}, "json");
			
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
			
			champs.each(function(i)
			{
				var champ = $(this);
				var valDefaut = champ.attr('data-val-defaut');
				var groupeChamps = champ.parents('.groupe');
				var groupeId = groupeChamps.attr('data-groupe-id');
				var groupe = colIntitules.find('.groupe[data-groupe-id='+groupeId+']');
				var groupePrec = groupe.prev('.groupe');
				var groupeChampsPrec = groupeChamps.prev('.groupe');
				var champPremier = champ.hasClass('premier');
				var champDernier = champ.hasClass('dernier');
				var champDernierGroupePrec = groupeChampsPrec.find('input.dernier');
				
				// Focus sur la colonne courante s'il n'y pas de colonne active
				// et si la colonne courante n'a pas déjà le focus
				champ.focus(function()
				{
					if(!colActive && !colonne.hasClass('col_focus')) $.majColFocus(colonne, true);
				});
				
				
				// Sélectionne le texte du champ au clic
				if(champ.is(':text') && !champ.attr('readonly'))
				{
					champ.click(function(e)
					{
						champ.select();
						e.preventDefault();
					});
				}
				
				// Tabultation inverse
				champ.keydown(function(e)
				{
					if(e.keyCode == 9 && e.shiftKey)
					{
						// Si le champ courant est le 1er d'un groupe
						// Et s'il y a un groupe précédent 
						if(groupePrec.exists() && champPremier)
						{
							champ.blur();
							
							// Si le groupe n'était pas ouvert ni bloqué au démarrage
							if(!groupe.hasClass('bloque') && !groupe.hasClass('demarrage-ouvert') )
							{
								groupe.trigger('fermer');
							}
							
							groupePrec.trigger('ouvrir');
							champDernierGroupePrec.focus();
							
							e.preventDefault();
						}
					}
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
		
		//colCurseur = colFocus.find('a.col_curseur');
		colCurseur = colFocus.find(colFocus.attr('data-input-focus'));
		
		if (colCurseur.length == 0)
		{
			colCurseur = colFocus.find('a.col_curseur');
		}
		/*else if(colCurseur.is('input'))
		{
		}*/

		colCurseur.focus();
		colCurseur.select();
		
		// Positionnement du scroll
		$.majColSaisiesScroll();
	};
	
	/**
	 * Change le focus sur les colonnes
	 * $.majColFocus(objet, garderChampFocus);
	 ******************************************/
	$.majColFocus = function(objet, garderChampFocus)
	{
		var colCurseur;
		var direction = false;
		
		if(!garderChampFocus) $(':focus').blur();
		
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
		
		// Boutons inactifs + masque
		btnEtapesDR.addClass('inactif');
		btnPrecSuivProd.addClass('inactif');
		masqueColActive.show();
		
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
			
			// Boutons actifs + suppression du  masque
			btnEtapesDR.removeClass('inactif');
			btnPrecSuivProd.removeClass('inactif');
			masqueColActive.hide();
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
			
			champ.saisieNum
			(
				float,
				function(){ colonne.majColActive(false); },
				function()
				{
					$.calculerSommesChamps();
					$.calculerChampsInterdependants();
				}
			);
		});
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
	 * Calcul des valeurs des champs interdépendants
	 * $.calculerChampsInterdependants();
	 ******************************************/
	$.calculerChampsInterdependants = function()
	{
		if(colActive)
		{
			var champsCalcul = colActive.find('input[data-calcul]');
			
			// Parcours des champs à calcul automatique
			champsCalcul.each(function()
			{
				var champCalcul = $(this);
				var type = champCalcul.attr('data-calcul');
				var tabChamps = champCalcul.attr('data-champs').split(';');
				var radioName = champCalcul.attr('data-radio-name');
				var resultat=0;
				var val = 0;

				champCalcul.removeClass('positif').removeClass('negatif');
				

				// Parcours des champs concernés
				for(var i = 0; i < tabChamps.length; i++)
				{
					val = parseFloat($(tabChamps[i]).val());
					if (!val) val = 0;

					if(i == 0) resultat = val;
					else
					{
						if(type == 'somme') resultat += val;
						else if(type == 'diff') resultat -= val;
						else if(type == 'produit' || type == 'produit_radio') resultat *= val;
					}
				}

				if(type == 'produit_radio')
				{
					resultat *= parseFloat($(radioName+' input:radio:checked').val());
					resultat *= 0.01;
				}

				resultat = resultat.toFixed(2);

				champCalcul.val(resultat);

				if(resultat > 0) champCalcul.addClass('positif');
				else if(resultat < 0)  champCalcul.addClass('negatif'); 
			});
		}
	};

	/**
	 * Choix par boutons radio
	 * $.initChoixRadio();
	 ******************************************/
	$.initChoixRadio = function()
	{
		var listes = colSaisies.find('.choix_radio');

		// Parcours de toutes les listes de choix
		listes.each(function()
		{
			var liste = $(this);
			var radios = liste.find('input:radio');
			/*var champObserve = $(liste.attr('data-observe'));
			var champResultat = liste.find('.resultat input');
			*/

			// Mise à jour des calculs
			radios.change(function()
			{
				$.calculerChampsInterdependants();
			});

			// Mise à jour de la valeur Lorsque le champ à observer
			/*if(champObserve.exists())
			{
				champObserve.blur(function()
				{
					radios.filter(':checked').trigger('change');
				});
			}*/
		});
	};

	
	/**
	 * Affiche/Masque les groupes de champs
	 * $.toggleGroupesChamps();
	 ******************************************/
	$.toggleGroupesChamps = function()
	{
		var groupesIntitules = colIntitules.find('.groupe');
		/*var groupeOuvert = colIntitules.find('.groupe[data-groupe-id=4]');
		var gpeAssocieOuvert = colSaisies.find('.groupe[data-groupe-id=4]');
		var gpeAssocieOuvertIntitules = gpeAssocieOuvert.children('p');
		
		gpeAssocieOuvertIntitules.find('input').focus(function()
		{
			var champ = $(this);
			var champSuivant = champ.parents('.groupe').find('ul input:first');
			
			if(!groupeOuvert.hasClass('groupe_ouvert'))
			{
				groupesIntitules.each(function()
				{						
					if($(this).hasClass('groupe_ouvert')) {
						$(this).removeClass('groupe_ouvert');
						$(this).children('ul').slideToggle();
						colSaisies.find('.groupe[data-groupe-id='+$(this).attr('data-groupe-id')+']').children('ul').slideToggle();
					}
				});
			}
		});*/
		
		
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
			
			
			// Evènement "fermer"
			groupe.bind('fermer',function()
			{
				groupe.removeClass('groupe_ouvert');
				listeIntitules.slideUp();
				gpeChamps.slideUp();
			});
			
			// Evènement "ouvrir"
			groupe.bind('ouvrir',function()
			{
				groupe.addClass('groupe_ouvert');
				listeIntitules.slideDown();
				gpeChamps.slideDown();
			});
			

			// Affiche / Masque les groupes et les champs associés
			if (!groupe.hasClass('bloque'))
			{
				titre.click(function()
				{
					if(groupe.hasClass('groupe_ouvert')) groupe.trigger('fermer');
					else groupe.trigger('ouvrir');
				});
			}
			
			// Parcours de tous les champs associés aux intitulés
			gpeAssocieIntitules.find('input').focus(function()
			{
				var champ = $(this);
				
				
				var champSuivant = champ.parents('.groupe').find('ul input.premier');
				
				// Si le groupe est fermé ou si c'est un groupe bloqué et ouvert
				if(!groupe.hasClass('groupe_ouvert') || (groupe.hasClass('bloque') && groupe.hasClass('demarrage-ouvert')))
				{
					// Fermeture de tous les autres groupes
					// sauf les groupes bloqués
					groupesIntitules.each(function()
					{
						var gpeInt = $(this);
						
						if(gpeInt.hasClass('groupe_ouvert') && !gpeInt.hasClass('bloque')) gpeInt.trigger('fermer');
					});
					
					// Ouverture du groupe courant
					groupe.trigger('ouvrir');
					
					// Focus sur le champ suivant si le champ courant n'est pas éditable 
					if(champ.attr('readonly')) champSuivant.focus();
				}
			});
			if(window.location.hash) {
				var anchor = window.location.hash.substring(1);
				if (anchorIds[anchor] == groupe.attr('data-groupe-id')) {
					groupe.trigger('ouvrir');
				}
			} else if(groupe.hasClass('demarrage-ouvert')) {
				groupe.trigger('ouvrir');
			}
		});
	};
	
})(jQuery);