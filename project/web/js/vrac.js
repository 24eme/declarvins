/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


(function($)
{
	
	 var init = function() {
		var tableau = $('#vrac_soussigne').find('table');
		var listStyleTableau = $('#recap_saisie').find('ol li');
		
		listStyleTableau.find('li:even').addClass('impair');
		tableau.find('tr:even').addClass('impair');
	}

	var initAutoComplete = function() {
	    $('.autocomplete').combobox();
	}

	$.fn.initBlocCondition = function()
	{
		$(this).find('.bloc_condition').each(function() {
			checkUncheckCondition($(this));
		});
	}

	var checkUncheckCondition = function(blocCondition)
    {
    	var input = blocCondition.find('input');
    	var blocs = blocCondition.attr('data-condition-cible').split('|');
    	console.log(blocs);
    	var traitement = function(input, blocs) {
		if(input.is(':checked') || input.is(':selected'))
            {
        	   for (bloc in blocs) {
        		   if ($(blocs[bloc]).exists()) {
            		   var values = $(blocs[bloc]).attr('data-condition-value').split('|');
            		   for(key in values) {
            			   if (values[key] == input.val()) {
            				   $(blocs[bloc]).show();
            				   $(blocs[bloc]).initBlocsFormCol();	
            			   }
            		   }
        		   }
        	   }
            }
    	}
    	if(input.length == 0) {
     	   for (bloc in blocs) {
  				$(blocs[bloc]).show();
     	   }
    	} else {
     	   for (bloc in blocs) {
  				$(blocs[bloc]).hide();
     	   }
    	}
    	input.each(function() {
    		traitement($(this), blocs);
    	});

        input.click(function()
        {
      	   for (bloc in blocs) {
 				$(blocs[bloc]).hide();
    	   }
      	   if($(this).is(':checkbox')) {
          	   $(this).parent().find('input').each(function() {
	        	   traitement($(this), blocs);
          	   });
      	   } else {
      		   traitement($(this), blocs);
      	   }
        });
	}

	/* Egalisation des lignes pour les champs en 2 colonnes */
	$.fn.initBlocsFormCol = function()
	{
		var blocsForm = $(this);

		blocsForm.each(function()
		{
			var blocForm = $(this);
			var cols = blocForm.find('.col');
			var lignes = cols.eq(0).find('.vracs_ligne_form');
			var nbLignes = lignes.size();

			for(var i=0; i<nbLignes; i++)
			{
				cols.find('.vracs_ligne_form:nth-child('+i+')').hauteurEgale();
			}
		});
	}


	
	var initSoussigne = function() {
	   if(!$('.vrac_soussigne').exists())
	   {
	       return;
	   }
	   $('.bloc_adresse input[type=checkbox]').change(function() {
		   var checkbox = $(this);
		   if (!checkbox.attr('checked')) {
			   checkbox.parents('.bloc_adresse').find('input[type=text]').each(function() {
				   $(this).val('');
			   });
		   }
	   });
	};
	
	var initConditions = function()
	{
	     if($('.vrac_condition').exists())
	     {
	    	 
	    	 return;
	     }

		
	    if($('#vrac_condition #type_contrat input:checked').length == 0)
	        $('#vrac_condition #type_contrat input[value="spot"]').attr('checked','checked');
	    if($('#vrac_condition #prix_isVariable input:checked').length == 0)
	        $('#vrac_condition #prix_isVariable input[value="0"]').attr('checked','checked');
	    updatePanelsConditions();
	    $('#vrac_condition #type_contrat input').click(updatePanelsConditions);
	    $('#vrac_condition #prix_isVariable input').click(updatePanelsConditions);
	    
	}
	
	
	var updatePanelsConditions = function()
	{
	    if($('#vrac_condition #type_contrat input:checked').val()=='spot')
	    {
	        $('section#prix_isVariable').hide();
	    }
	    else
	    {
	        $('section#prix_isVariable').show();
	        if($('#vrac_condition #prix_isVariable input:checked').val()=='0')
	        {
	            $('section#prix_variable').hide();
	        }
	        else
	        {
	            $('section#prix_variable').show();
	        }
	    }
	}
	
	var initMarche = function()
	{
		
		if(!$('.vrac_marche').exists())
	    {
			
			return;
	    }
		
	    if($('#vrac_marche #original input:checked').length == 0)
	        $('#vrac_marche #original input[value="1"]').attr('checked','checked');
	    if($('#vrac_marche #type_transaction input:checked').length == 0)
	        $('#vrac_marche #type_transaction input[value="vin_vrac"]').attr('checked','checked');       
	   updatePanelsAndUnitLabels();    
	   $('#vrac_marche #type_transaction input').click(updatePanelsAndUnitLabels);
	
        var sommeEuros = function(val1, total, cotisation, val3, val4, hasCotisationCvo)
        {
            val1.keyup(function()
            {
                var thisVal = parseFloat($(this).val());
                if(isNaN(thisVal)) {
                	thisVal = 0;                
                }
                var cotis = parseFloat(cotisation.text());
                if(!isNaN(thisVal) && !isNaN(cotis))
                {
                    if (hasCotisationCvo != 0) {
                		total.val(thisVal + cotis); 
                	}
                }
            });
        }
        var prix = $('#vrac_marche_prix_unitaire');
        var totalSomme = $('#vrac_marche_prix_total_unitaire');
        var hasCotisationCvo = $('#vrac_marche_has_cotisation_cvo').val();
        var cotisation = $('#vrac_cotisation_interpro');
        var tauxRepartition = $('#vrac_marche_repartition_cvo_acheteur');
        var tauxCVO = $('#vrac_marche_part_cvo');
        if (hasCotisationCvo && prix.val()) {
        	totalSomme.val(parseFloat(prix.val()) + parseFloat(cotisation.text())); 
        }
        sommeEuros(prix, totalSomme, cotisation, tauxRepartition, tauxCVO, hasCotisationCvo);
	    
	}
	
	
	var updatePanelsAndUnitLabels = function()
	{
	     switch ($('#vrac_marche #type_transaction input:checked').attr('value'))
	    {
	         case 'raisins' :
	        {
	            updatePanelsAndUnitForRaisins();
	            $('#vrac_raisin_quantite').keyup(updatePanelsAndUnitForRaisins);
	            $('#vrac_prix_unitaire').keyup(updatePanelsAndUnitForRaisins);
	            $('#vrac_raisin_quantite').click(updatePanelsAndUnitForRaisins);
	            $('#vrac_prix_unitaire').click(updatePanelsAndUnitForRaisins);
	            break;
	        }
	        case 'mouts' :
	        {
	            updatePanelsAndUnitForJuice();
	            $('#vrac_jus_quantite').keyup(updatePanelsAndUnitForJuice);
	            $('#vrac_prix_unitaire').keyup(updatePanelsAndUnitForJuice);
	            $('#vrac_prix_unitaire').click(updatePanelsAndUnitForJuice);
	            $('#vrac_jus_quantite').click(updatePanelsAndUnitForJuice);
	            break;
	        }
	        case 'vin_vrac' :
	        {
	            updatePanelsAndUnitForJuice();
	            $('#vrac_jus_quantite').keyup(updatePanelsAndUnitForJuice);
	            $('#vrac_prix_unitaire').keyup(updatePanelsAndUnitForJuice); 
	            $('#vrac_prix_unitaire').click(updatePanelsAndUnitForJuice);   
	            $('#vrac_jus_quantite').click(updatePanelsAndUnitForJuice);         
	            break;
	        }
	        case 'vin_bouteille' :
	        {
	            updatePanelsAndUnitForBottle();
	            $('#vrac_bouteilles_quantite').keyup(updatePanelsAndUnitForBottle);            
	            $('#vrac_prix_unitaire').keyup(updatePanelsAndUnitForBottle);
	            $('#vrac_bouteilles_quantite').click(updatePanelsAndUnitForBottle);            
	            $('#vrac_prix_unitaire').click(updatePanelsAndUnitForBottle);
	            $('#vrac_bouteilles_contenance').change(updatePanelsAndUnitForBottle);
	            break;
	        }
	    }
	    
	    if($('#type input:checked').length == 0)
	        $('#type input[value="domaine"]').attr('checked','checked');   
	    
	    if($('#type input[value="generique"]:checked')) $('#domaine').hide();
	    
	    $('#type input').click(function()
	    {
	        if($(this).val()=='generique') $('#domaine').hide();
	        else  $('#domaine').show();       
	    });
	    
	}
	
	var updatePanelsAndUnitForRaisins = function()
	{
	    jQuery('section.bouteilles_contenance').hide();
	    jQuery('section div.bouteilles_quantite').hide();
	    jQuery('section div.jus_quantite').hide();    
	    jQuery('section div.raisin_quantite').show();
	    
	
	    majTotal("raisin_quantite","(en kg)","€/kg");  
	}
	
	var updatePanelsAndUnitForJuice = function()
	{
	    jQuery('section.bouteilles_contenance').hide();
	    jQuery('section div.bouteilles_quantite').hide();    
	    jQuery('section div.raisin_quantite').hide();    
	    jQuery('section div.jus_quantite').show();
	    
	    majTotal("jus_quantite","(en hl)","€/hl");    
	}
	
	var updatePanelsAndUnitForBottle = function()
	{     
	    jQuery('section div.raisin_quantite').hide();
	    jQuery('section div.jus_quantite').hide();
	    
	    jQuery('section.bouteilles_contenance').show();
	    jQuery('section div.bouteilles_quantite').show();
	    
	    var volume_total = 0.0;
	    var bouteilles_quantite = jQuery('#vrac_bouteilles_quantite').val();
	    var bouteilles_contenance = jQuery('#vrac_bouteilles_contenance').val();
	    if(bouteilles_quantite == "" || bouteilles_contenance == "") return; 
	    
	    var numeric =  new RegExp("^[0-9]*$","g");
	    
	    if(numeric.test(bouteilles_quantite))
	    {
	        volume_total = (parseInt(bouteilles_contenance)/10000) * parseInt(bouteilles_quantite);
	        jQuery('div.bouteilles_quantite span#volume_unite_total').text("(soit "+volume_total+" hl)");
	        var bouteilles_price = jQuery('#vrac_prix_unitaire').val();
	        var bouteilles_priceReg = (new RegExp("^[0-9]*[.][0-9]{2}$","g")).test(bouteilles_price);
	        if(bouteilles_priceReg)
	        {
	           var prix_total = parseInt(bouteilles_quantite) * parseFloat(bouteilles_price);
	           jQuery('#vrac_prix_total').text(prix_total);
	           var prix_hl = prix_total / volume_total;
	           jQuery('section#prixUnitaire span#prix_unitaire_unite').text("€/btlle");
	           jQuery('section#prixUnitaire span#prix_unitaire_hl').text("(soit "+prix_hl+" €/hl)");
	        }
	    }
	}
	
	var majTotal = function(quantiteField,unite,prixParUnite){
	    var quantite = jQuery('#vrac_'+quantiteField).val();
	    var numeric =  new RegExp("^[0-9]*$","g");
	    
	    if(numeric.test(quantite))
	    {
	        jQuery('div.'+quantiteField+' span#volume_unite_total').text(unite);
	        var prix_unitaire = jQuery('#vrac_prix_unitaire').val();
	        var priceReg = (new RegExp("^[0-9]*[.][0-9]{2}$","g")).test(prix_unitaire);
	        if(priceReg)
	        {
	           var prix_total = quantite * parseFloat(prix_unitaire);
	           jQuery('#vrac_prix_total').text(prix_total);
	           jQuery('section#prixUnitaire span#prix_unitaire_unite').text(prixParUnite);
	        }
	    }
	}
	
	var majAutocompleteInteractions = function(type)
	{
	    $('#'+type+'_choice div input').live( "autocompleteselect", function(event, ui)
	    {
	        $('#'+type+'_modification_btn').removeAttr('disabled');
	        $('#'+type+'_modification_btn').css('cursor','pointer');        
	    });
	}
	
	var majModificationsButton = function(type)
	{
	    if($('section#'+type+'_choice input.ui-autocomplete-input').val()=="") $('#'+type+'_modification_btn').attr('disable','disable');
	    else $('#'+type+'_modification_btn').removeAttr('disable');
	}
	
	
	var majMandatairePanel = function()
	{
	    if($('section#has_mandataire input').attr('checked')) {$('section#mandataire').show();}
	    else{$('section#mandataire').hide();}
	    
	    $('section#has_mandataire input').click(function()
	    {
	        if($(this).attr('checked'))
	        {
	            $('section#mandataire').show();
	            $('#vrac_mandatant_vendeur').attr('checked','checked');            
	        }
	        else
	        {
	            $('section#mandataire').hide();
	            $('section#mandataire input').each(function()
	            {
	                
	                if($(this).attr('type')=='checkbox') $(this).attr('checked',false);
	                else 
	                {
	                    if($(this).attr('type')!='button') $(this).val('');
	                }
	            });
	        }
	    });
	    
	    $('#vrac_mandatant_vendeur').click(function()
	    {
	        if(($('#mandatant input:checked').length == 0) && ($('#vrac_mandatant_vendeur:checked'))) $('#vrac_mandatant_vendeur').attr('checked','checked');
	    });
	    $('#vrac_mandatant_acheteur').change(function()
	    {
	        if(($('#mandatant input:checked').length == 0) && ($('#vrac_mandatant_acheteur:checked'))) $('#vrac_mandatant_acheteur').attr('checked','checked');
	    });
	    
	}
	
	var init_ajax_modification = function(type)
	{
	    $('a#'+type+'_modification_btn').html('Valider');
	    $('a#'+type+'_modification_btn').removeClass('btn_orange').addClass('btn_vert');
	    $('a#'+type+'_modification_btn').css('cursor','pointer');
	    
	    $("#"+type+"_choice input").attr('disabled','disabled');
	    $("#"+type+"_choice button").attr('disabled','disabled');
	    $('div.btnValidation button').attr('disabled','disabled');
	    $("#"+type+"_choice input").addClass('disabled');
	    $("#"+type+"_choice button").addClass('disabled');
	    $('div.btnValidation button').addClass('disabled');
	    var params = {id : $("#vrac_"+type+"_identifiant").val(), 'div' : '#'+type+'_informations'};  
	    ajaxifyPost('modification?type='+type+'&id='+$("#vrac_"+type+"_identifiant").val(),params,'#'+type+'_modification_btn','#'+type+'_informations');
	}
	
	
	var init_informations = function(type)
	{
	    $("#"+type+"_choice input").removeAttr('disabled');
	    $("#"+type+"_choice button").removeAttr('disabled');
	    
	    $("a#"+type+"_modification_btn").html("Modifier");
	    $("a#"+type+"_modification_btn").removeClass('btn_vert').addClass('btn_orange');
	    
	    
	    $("#"+type+"_modification_btn").unbind();
	    $('div.btnValidation button').removeAttr('disabled');
	}
	    
	var initCollectionAddTemplate = function(element, regexp_replace, callback)
	{
		
	    $(element).live('click', function()
	    {
	        var bloc_html = $($(this).attr('data-template')).html().replace(regexp_replace, UUID.generate());



	        try {
				var params = jQuery.parseJSON($(this).attr('data-template-params'));
			} catch (err) {

	        }

			for(key in params) {
				bloc_html = bloc_html.replace(new RegExp(key, "g"), params[key]);
			}

	        var bloc = $($(this).attr('data-container')).append(bloc_html);

	        if(callback) {
	        	callback(bloc);
	        }
	        return false;
	    });
	}
	    
	var initFamilleEtablissementTemplate = function(element, regexp_replace)
	{
		
	    $(element).live('change', function()
	    {
	    	resetEtablissement($(this).parents('.vrac_vendeur_acheteur'), false);
	    	var target = $($(this).attr('data-container'));
	    	var targetAutoComplete = target.next(".ui-autocomplete-input");
	    	var template = $($(this).attr('data-template'));
	    	if (targetAutoComplete.attr('disabled')) {
	    		targetAutoComplete.removeAttr('disabled');
	    		targetAutoComplete.removeClass('disabled');
	    	}
	    	target.attr('data-ajax', template.text().replace(regexp_replace, $(this).val()));
	    });
	}
	    
	var initEtablissements = function()
	{
	    $('.vrac_vendeur_acheteur').each(function()
	    {
	    	initEtablissement($(this));
	    });
	    
	    var radio_vous_etes = $('#bloc_vous_etes input:radio');
	    radio_vous_etes.click(function () {
		    $('.soussigne_form_choice').each(function() {
		    	resetEtablissement($(this), true);
		    })
	    });
	}

	var resetEtablissement = function (bloc, resetFamille) {
		var select = bloc.find('.etablissement_choice select');
		var input_autcomplete = bloc.find('.etablissement_choice input');
		var etablissement_informations = bloc.find('.etablissement_informations');

		if(resetFamille) {
			var inputs_famille = bloc.find('.etablissement_famille_choice input');
			inputs_famille.removeAttr('checked');
			initEtablissement(bloc);
		}

		select.html('');
		input_autcomplete.val('');
		etablissement_informations.find('textarea').html('');
		etablissement_informations.find('input:text').val('');
		etablissement_informations.find('input:checkbox').removeAttr('checked');
		etablissement_informations.find('input:radio').removeAttr('checked');
		etablissement_informations.find('select').html('');
	}

	var initEtablissement = function (bloc) {
		var radioButton =  $($('#'+bloc.attr('id')+' :radio').eq(0));
    	var target = $(radioButton.attr('data-container')).next(".ui-autocomplete-input");
    	var nbChecked = $('#'+bloc.attr('id')+' :radio:checked').length;
    	if (!nbChecked) {
    		target.attr("disabled","disabled");
    	}
	}
	
	var initCollectionDeleteTemplate = function()
	{
		$('.btn_supprimer_ligne_template').live('click',function()
	    {
	    	var element = $(this).attr('data-container');
	        $(this).parents(element).remove();
	
	        return false;
	    });
	}
	
	var initChoicesListener = function()
	{
		ajaxifyAutocomplete('#listener_acheteur_choice', 'acheteur', '#template_url_informations');
		ajaxifyAutocomplete('#listener_vendeur_choice', 'vendeur', '#template_url_informations');
		ajaxifyAutocomplete('#listener_mandataire_choice', 'mandataire', '#template_url_informations');
	}
	
	var initProductListener = function()
	{
		ajaxifyProductAutocomplete('#listener_product');
	}
	
	var ajaxifyAutocomplete = function(listenerChoice, type, templateUrl)
	{
		var select = $(listenerChoice+' select');
		var input = $(listenerChoice+' input');
		var container = $(select.attr('data-infos-container'));
		input.live( "autocompleteselect", function(event, ui) {
			var url = $(templateUrl).text().replace(/var---etablissement---/g, $(ui.item.option).val());
			var url = url.replace(/var---type---/g, type);
			$.get(url, function(data){
				container.html(data);
			});
		});
	}
	
	var ajaxifyProductAutocomplete = function(listenerChoice)
	{
		var select = $(listenerChoice+' select');
		var input = $(listenerChoice+' input');
		input.live( "autocompleteselect", function(event, ui) {
			var hash = $(ui.item.option).val();
			hash = hash.replace(/\//g, "-");
		});
	}
	
	var initTransactions = function()
	{
		if (!$('#contenu.vrac_transaction').exists()) {
			return;
		}
			
	}
	
    var callbackAddTemplate = function(bloc) 
    {
   	 	bloc.initBlocCondition();
   	 	bloc.find('.datepicker').datepicker(dpConfig); 
   	 	if ($(".drm_vrac_contrats").length > 0) {
   	 		$(".drm_vrac_contrats").combobox(); 
   	 	}
   	 	if ($("#filtre_produits_items").length > 0) {
   	 		$("#filtre_produits_items select").last().combobox();
   	 	}
   	 	if ($("#filtre_etablissements_items").length > 0) {
   	 		$("#filtre_etablissements_items select").last().combobox();
   	 	}
   	 	if ($("#filtre_vendeur_identifiant").length > 0) {
   	 		$("#filtre_vendeur_identifiant select").last().combobox();
   	 	}
   	 	if ($("#filtre_acheteur_identifiant").length > 0) {
   	 		$("#filtre_acheteur_identifiant select").last().combobox();
   	 	}
   	 	if ($("#filtre_mandataire_identifiant").length > 0) {
   	 		$("#filtre_mandataire_identifiant select").last().combobox();
   	 	}
    }

	$(document).ready(function()
	{
		 $(this).initBlocCondition();
		 $('.bloc_form').has('.col').initBlocsFormCol();

		 init();
	     initSoussigne();
	     initMarche();
	     initConditions();
	     initTransactions();
	     initAutoComplete();
	     initEtablissements();
	     initChoicesListener();
	     initProductListener();

	     initCollectionAddTemplate('.btn_ajouter_ligne_template', /var---nbItem---/g, callbackAddTemplate);
	     initCollectionAddTemplate('.btn_ajouter_ligne_template_sub', /var---nbItem---/g, callbackAddTemplate);
	     initFamilleEtablissementTemplate('.famille', /var---famille---/g);
	     initCollectionDeleteTemplate();
	});
})(jQuery);