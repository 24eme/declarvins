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
	var anchorIdsAcq = {"entrees" : 6, "sorties" : 7}
	// Variables globales 
	var colonnesDRAcq = $('#colonnes_dr_acq');
	var colIntitulesAcq = $('#colonne_intitules_acq');
	var colSaisiesAcq = $('#col_saisies_acq');
	var colSaisiesAcqCont = $('#col_saisies_cont_acq');
	var colSaisiesAcqRecolte = colSaisiesAcqCont.find('.col_recolte');
	var colTotalAcq = $('#colonne_total');
	
	var colActiveAcqDefaut = colSaisiesAcqRecolte.filter('.col_active');
	var colActiveAcq;
	var colEditeeAcq;
	
	var colFocusAcq;
	var colFocusAcqNum = 1; // Colonne qui a le focus par défaut
	
	var btnAjouterAcq = colonnesDRAcq.find('.btn_ajouter');
	
	var btnEtapesDRAcq = $('#btn_etape_dr');
	var btnPrecSuivProdAcq = $('#btn_suiv_prec');

	var masquecolActiveAcq;
	
	$(document).ready( function()
	{
		if(colonnesDRAcq.exists())
		{
			$.initColonnesAcq();
			$.verifierChampsNombreAcq();
			$.calculerSommesChampsAcq();
			$.calculerChampsInterdependantsAcq();
			$.toggleGroupesChampsAcq();
			$.initChoixRadioAcq();
		}
	});
	

	
	/**
	 * Calcul dynamique des dimmensions des colonnes
	 * $.initColonnesAcq();
	 ******************************************/
	$.initColonnesAcq = function()
	{
		var colEgales = colIntitulesAcq.add(colSaisiesAcqCont).add(colTotalAcq);
		var colSaisiesAcqActive = colSaisiesAcqRecolte.filter('.col_active');
		var largeurCSC = 0;
		
		
		// Ajout du masque
		colSaisiesAcqRecolte.append('<div class="col_masque"></div>');
		
		// Calcul de la largeur du conteneur des colonnes de saisies
		colSaisiesAcqRecolte.each(function()
		{
			largeurCSC += $(this).outerWidth(true);
		});
		
		colSaisiesAcqCont.width(largeurCSC);
	
		// Initialisation des actions des boutons des colonnes
		$.initColBoutonsAcq();
		
		// Initialisation des actions associées aux raccourci clavier
		$.initRaccourcisAcq();
		
		// Initialisation le comportement des champs au focus et à la saisie
		$.initComportementsChampsAcq();
		
		// Positionnement du focus par défaut
		$.initcolFocusAcq();
		
		// Egalisation de la hauteur des colonnes et des titres
		colEgales.find('.couleur, h2').hauteurEgale();
		colEgales.find('.label').hauteurEgale();
		
		$.initMasquecolActiveAcq();
		
		// Colonne active par défaut
		if(colActiveAcqDefaut.exists()) colActiveAcqDefaut.majcolActiveAcq();
	};
	
	/**
	 * Initialise le masque qui désactive les 
	 * liens lorque une colonne est active
	 * $.initMasquecolActiveAcq();
	 ******************************************/
	$.initMasquecolActiveAcq = function()
	{
		var hauteur = $('#colonnes_dr').position().top;
		masquecolActiveAcq = $('<div id="masque_col_active_acq"></div>').height(hauteur).hide();
		
		$('#contenu').append(masquecolActiveAcq);
	};
	
	/**
	 * Positionne le scroll en fonction de la
	 * colonne avec le focus / activée
	 * $.majcolSaisiesAcqScroll();
	 ******************************************/
	$.majcolSaisiesAcqScroll = function()
	{
		if(colActiveAcq && colActiveAcq.size() > 0) colSaisiesAcq.scrollTo(colActiveAcq, 200);
		else if(colFocusAcq.size() > 0) colSaisiesAcq.scrollTo(colFocusAcq, 200);
		else colSaisiesAcq.scrollTo({top: 0, left: 0}, 200);
	};
	
	/**
	 * Initialisation des actions des boutons des colonnes
	 * $.initColBoutonsAcq();
	 ******************************************/
	$.initColBoutonsAcq = function()
	{
		colSaisiesAcqRecolte.each(function()
		{
			var col = $(this);
			var boutons = col.find('.col_btn button');
			var btnReinitialiser = boutons.filter('.btn_reinitialiser');
			var btnValider = boutons.filter('.btn_valider');
			
		
			
			// Réinitialisation des valeurs d'une colonne
			btnReinitialiser.click(function()
			{
				$.reinitialiserColAcq();
				return false;
			});
			
			// Validation des valeurs d'une colonne
			btnValider.click(function()
			{
				$.validerColAcq();
				return false;
			});
		});
	};
	
	
	/**
	 * Initialisation des actions associées 
	 * aux raccourci clavier
	 * $.initRaccourcisAcq();
	 ******************************************/
	$.initRaccourcisAcq = function(col)
	{
		// Ctrl + flèche gauche ==> Changement de focus
		$.ctrl(37, function() { $.majcolFocusAcq('prec'); });
		
		// Ctrl + flèche droite ==> Changement de focus
		$.ctrl(39, function() { $.majcolFocusAcq('suiv'); });
		
		// Ctrl + M ==> Commencer édition colonne avec focus
		$.ctrl(77, function () { colFocusAcq.majcolActiveAcq(true); });
		
		// Ctrl + touche supprimer ==> Suppression colonne avec focus
		//$.ctrl(46, function() { colFocusAcq.find('.btn_supprimer').trigger('click'); });
		
		// Ctrl + Entrée ==> Validation de la colonne active
		$.ctrl(13, function() { colFocusAcq.find('.btn_valider').trigger('click'); });
	};
		
	/**
	 * Supprimer la colonne demandée
	 * $.fn.supprimerColAcq();
	 ******************************************/
	$.fn.supprimerColAcq = function()
	{
		// S'il n'y a pas de colonne active définie
		if(!colActiveAcq)
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
	 * $.reinitialiserColAcq();
	 ******************************************/
	$.reinitialiserColAcq = function()
	{
		// S'il y a une colonne active définie
		if(colActiveAcq)
		{
			var champs = colActiveAcq.find('input:text, select');
			
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
			
			$.enlevercolActiveAcq();
		}
	};
	
	/**
	 * Valide les valeurs de la colonne en cours
	 * et les envoie en AJAX
	 * $.validerColAcq();
	 ******************************************/
	$.validerColAcq = function()
	{
		// S'il y a une colonne active définie
		if(colActiveAcq)
		{
			$.calculerSommesChampsAcq();
			$.calculerChampsInterdependantsAcq();
			
			var form = colActiveAcq.find('form');
			var donneesCol = form.serializeArray();
			
			colActiveAcq.addClass('col_envoi');
			/*var btn = colActiveAcq.find('.col_btn button');
			
			btn.css('visibility', 'hidden');
			*/
		
			
			$.post(form.attr('action'), donneesCol, function (data)
			{
				if(data.success)
				{
					var champs = colActiveAcq.find('input:text, select');
					
					champs.each(function()
					{
						var champ = $(this);
						var val = champ.val();
						var val_defaut = parseFloat(champ.attr('data-val-defaut'));
						if (isNaN(val_defaut)) {
							val_defaut = '';
						} else {
							val_defaut = val_defaut.toFixed(4);
						}
						if (val_defaut != val) {
							if (colActiveAcq.attr('data-cssclass-rectif')) {
								champ.parent().addClass(colActiveAcq.attr('data-cssclass-rectif'));
							}
						}
						
						champ.attr('data-val-defaut', val);
					});

					var cond = /^drm_detail\[(entrees|sorties)\]/;
					var condDaids = /^daids_detail\[stocks\]\[(chais|propriete_tiers|tiers)\]/;
					var totalCol = 0;
					var totalColDaids = 0;
					for (var i in donneesCol) {
						if ((donneesCol[i].name).match(cond) && !isNaN(donneesCol[i].value) && donneesCol[i].value) {
							totalCol += parseFloat(donneesCol[i].value);
						}
						if ((donneesCol[i].name).match(condDaids) && !isNaN(donneesCol[i].value) && donneesCol[i].value) {
							totalColDaids += parseFloat(donneesCol[i].value);
						}
					}
					if (totalCol > 0 || totalColDaids > 0) {
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
					colIntitulesAcq.find('.groupe').each(function()
					{
						var groupe = $(this);
						
						if(groupe.hasClass('groupe_ouvert') && !groupe.hasClass('bloque')) groupe.trigger('fermer');

						if(groupe.hasClass('demarrage-ouvert') && !groupe.hasClass('bloque')) groupe.trigger('ouvrir');
					});
				}
				
				$('#forms_errors').html(data.content);
				
				//btn.css('visibility', 'visible');
				colActiveAcq.removeClass('col_envoi');
				$.enlevercolActiveAcq();
			}, "json");
			
		}
	};
	
	
	/**
	 * Initialise le comportement des champs 
	 * au focus et à la saisie
	 * $.initComportementsChampsAcq();
	 ******************************************/
	$.initComportementsChampsAcq = function()
	{
		colSaisiesAcqRecolte.each(function()
		{
			var colonne = $(this);
			var champs = colonne.find('input:text, select');
			
			champs.each(function(i)
			{
				var champ = $(this);
				var valDefaut = champ.attr('data-val-defaut');
				var groupeChamps = champ.parents('.groupe');
				var groupeId = groupeChamps.attr('data-groupe-id');
				var groupe = colIntitulesAcq.find('.groupe[data-groupe-id='+groupeId+']');
				var groupePrec = groupe.prev('.groupe');
				var groupeChampsPrec = groupeChamps.prev('.groupe');
				var champPremier = champ.hasClass('premier');
				var champDernier = champ.hasClass('dernier');
				var champDernierGroupePrec = groupeChampsPrec.find('input.dernier');
				
				// Focus sur la colonne courante s'il n'y pas de colonne active
				// et si la colonne courante n'a pas déjà le focus
				champ.focus(function()
				{
					if(!colActiveAcq && !colonne.hasClass('col_focus')) $.majcolFocusAcq(colonne, true);
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
						if(!colActiveAcq && val != valDefaut) { colEditeeAcq = colonne; colonne.majcolActiveAcq(false); }
					});
					
					// Si la valeur du champ change alors la colonne est activée
					champ.change(function()
					{
						if(!colActiveAcq) colonne.majcolActiveAcq(false);
					});
				}
			});
		});
	};
	
	
	/**
	 * Initialise et gère le focus sur les colonnes
	 * $.initcolFocusAcq();
	 ******************************************/
	$.initcolFocusAcq = function()
	{
		var colCurseurs = colSaisiesAcqRecolte.find('a.col_curseur');
		
		/*if(colFocusAcqDefaut) colFocusAcqNum = colFocusAcqDefaut;
		else*/ colFocusAcqNum = colCurseurs.first().attr('data-curseur');
		
		// Colonne au focus par défaut
		colFocusAcq = $('#col_recolte_'+colFocusAcqNum);
		colFocusAcq.addClass('col_focus');
		
		//colCurseur = colFocusAcq.find('a.col_curseur');
		colCurseur = colFocusAcq.find(colFocusAcq.attr('data-input-focus'));
		
		if (colCurseur.length == 0)
		{
			colCurseur = colFocusAcq.find('a.col_curseur');
		}
		/*else if(colCurseur.is('input'))
		{
		}*/

		colCurseur.focus();
		colCurseur.select();
		
		// Positionnement du scroll
		$.majcolSaisiesAcqScroll();
	};
	
	/**
	 * Change le focus sur les colonnes
	 * $.majcolFocusAcq(objet, garderChampFocus);
	 ******************************************/
	$.majcolFocusAcq = function(objet, garderChampFocus)
	{
		var colCurseur;
		var direction = false;
		
		if(!garderChampFocus) $(':focus').blur();
		
		// S'il n'y a pas de colonne active définie
		if(!colActiveAcq && !colEditeeAcq)
		{
			// Si c'est une direction
			if(typeof(objet) == "string") direction = objet;
			
			colFocusAcq.removeClass('col_focus');
			
			if(direction)
			{
				// Colonne précédente
				if(direction == 'prec')
				{
					if(colFocusAcq.prev().size() > 0) colFocusAcq = colFocusAcq.prev();
					else colFocusAcq = colSaisiesAcqRecolte.last();
				}
				// Colonne suivant
				else
				{
					if(colFocusAcq.next().size() > 0) colFocusAcq = colFocusAcq.next();
					else colFocusAcq = colSaisiesAcqRecolte.first();
				}
			}
			else { colFocusAcq = objet; }
			
			colFocusAcq.addClass('col_focus');
			colCurseur = colFocusAcq.find('a.col_curseur');
			colFocusAcqNum = colCurseur.attr('data-curseur');
			
			if(direction) colCurseur.focus();
			
			$.majcolSaisiesAcqScroll();
		}
	};
	
	
	/**
	 * Initialise l'activation d'un colonne
	 ******************************************/
	$.initcolActiveAcq = function()
	{
		
	};
	
	/**
	 * Active une colonne
	 * $(col).majcolFocusAcq();
	 ******************************************/
	$.fn.majcolActiveAcq = function(focusCurseur)
	{
		colActiveAcq = $(this);
		colFocusAcq.removeClass('col_focus');
		colActiveAcq.addClass('col_active').addClass('col_focus');
		colFocusAcq = colActiveAcq;
		colCurseur = colFocusAcq.find('a.col_curseur');
		colFocusAcqNum = colCurseur.attr('data-curseur');
		if(focusCurseur) colCurseur.focus();
		
		colActiveAcq.desactiverAutresColAcq();
		
		// Boutons inactifs + masque
		btnEtapesDRAcq.addClass('inactif');
		btnPrecSuivProdAcq.addClass('inactif');
		masquecolActiveAcq.show();
		
		$.majcolSaisiesAcqScroll();
		$.liaisonChampsInterdependantsAcq();
	};
	
	
	/**
	 * Désactive toutes les colonnes sauf celle que l'on édite
	 * $(col).desactiverAutresColAcq();
	 ******************************************/
	$.fn.desactiverAutresColAcq = function()
	{
		var colCourante = $(this);
		var colAutres = colSaisiesAcqRecolte.not(colCourante);
		var champsADesactiver = colAutres.find('input, select')
		
		// désactivation des champs
		colAutres.addClass('col_inactive');
		champsADesactiver.attr('disabled', 'disabled');
	};
	
	
	/**
	 * Supprime le statut de la colonne active
	 * et réactive tous les champs
	 * $.enlevercolActiveAcq();
	 ******************************************/
	$.enlevercolActiveAcq = function()
	{		
		if(colActiveAcq)
		{
			colActiveAcq.removeClass('col_active');
			colCurseur.focus();
			colActiveAcq = false;
			colEditeeAcq = false;
			
			// réactivation des champs
			colSaisiesAcqRecolte.removeClass('col_inactive');
			colSaisiesAcqRecolte.find('input, select').removeAttr('disabled');
			
			// Boutons actifs + suppression du  masque
			btnEtapesDRAcq.removeClass('inactif');
			btnPrecSuivProdAcq.removeClass('inactif');
			masquecolActiveAcq.hide();
		}
	};
	
	
	/**
	 * Met à jour les hauteurs des masques
	 * $.majHauteurMasqueAcq();
	 ******************************************/
	$.majHauteurMasqueAcq = function()
	{
		colSaisiesAcqRecolte.each(function()
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
	 * $.verifierChampsNombreAcq();
	 ******************************************/
	$.verifierChampsNombreAcq = function()
	{
		var champs = colSaisiesAcqRecolte.find('input.num');
	
		champs.each(function()
		{
			var champ = $(this);
			var colonne = champ.parents('.col_recolte');
			var float = champ.hasClass('num_float');
			
			champ.saisieNum
			(
				float,
				function(){ colonne.majcolActiveAcq(false); },
				function()
				{
					$.calculerSommesChampsAcq();
					$.calculerChampsInterdependantsAcq();
				}
			);
		});
	};
	
	
	/**
	 * Calcul des sommes des champs
	 * $.calculerSommesChampsAcq();
	 ******************************************/
	$.calculerSommesChampsAcq = function()
	{
		// Parcours des colonnes
		colSaisiesAcqRecolte.each(function()
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
				
				if(float) somme = somme.toFixed(4); // Arrondi à 2 chiffres après la virgule
				champSomme.attr('value', somme);
			});
		});
	};


	/**
	 * Calcul des valeurs des champs interdépendants
	 * $.calculerChampsInterdependantsAcq();
	 ******************************************/
	$.calculerChampsInterdependantsAcq = function()
	{
		if(colActiveAcq)
		{
			var champsCalcul = colActiveAcq.find('input[data-calcul]');
			
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
				var classes = champCalcul.attr('class').split(' '); // Tout ca parce que hasClass ne fonctionne pas ?!
				if (jQuery.inArray("inverse_value", classes) != -1) {
					resultat = resultat * (-1);
				}
				if (jQuery.inArray("not_null_value", classes) != -1 && resultat < 0) {
					resultat = 0;
				}
				if (jQuery.inArray("not_pos_value", classes) != -1 && resultat > 0) {
					resultat = 0;
				}
				resultat = resultat.toFixed(4);
				champCalcul.val(resultat);

				if(resultat > 0) champCalcul.addClass('positif');
				else if(resultat < 0)  champCalcul.addClass('negatif'); 
			});
		}
	};


	/**
	 * Liaison des champs interdépendants
	 * $.liaisonChampsInterdependantsAcq();
	 ******************************************/
	$.liaisonChampsInterdependantsAcq = function()
	{
		if(colActiveAcq)
		{
			var champsLiaison = colActiveAcq.find('input[data-liaison]');
			champsLiaison.each(function()
			{
				var champLiaison = $(this);
				champLiaison.blur(function() {
					var tabChamps = champLiaison.attr('data-liaison').split(';');
					champLiaison.removeClass('positif').removeClass('negatif');
					for(var i = 0; i < tabChamps.length; i++)
					{
						if (champLiaison.val()) {
							val = parseFloat(champLiaison.val());
							$(tabChamps[i]).val(val.toFixed(4));
						} else {
							$(tabChamps[i]).val(null);
						}
						
					}
					$.calculerChampsInterdependantsAcq();
				});
			});
		}
	};
	/**
	 * Choix par boutons radio
	 * $.initChoixRadioAcq();
	 ******************************************/
	$.initChoixRadioAcq = function()
	{
		var listes = colSaisiesAcq.find('.choix_radio');

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
				$.calculerChampsInterdependantsAcq();
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
	 * $.toggleGroupesChampsAcq();
	 ******************************************/
	$.toggleGroupesChampsAcq = function()
	{
		var groupesIntitules = colIntitulesAcq.find('.groupe');
		
		
		groupesIntitules.each(function()
		{
			var groupe = $(this);
			var gpeId = groupe.attr('data-groupe-id');
			var titre = groupe.children('p');
			var listeIntitules = groupe.children('ul');
			var intitules = listeIntitules.children();
			
			var gpeAssocie = colSaisiesAcq.find('.groupe[data-groupe-id='+gpeId+']');
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
			//$.majHauteurMasqueAcq();
			
			
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
				if (anchorIdsAcq[anchor] == groupe.attr('data-groupe-id')) {
					groupe.trigger('ouvrir');
				}
			} else if(groupe.hasClass('demarrage-ouvert')) {
				groupe.trigger('ouvrir');
			}
		});
	};
	
})(jQuery);