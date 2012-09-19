/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


(function($)
{

	var initAutoComplete = function() {
	    $('.autocomplete').combobox();
	}

	var checkUncheck = function(cibleInput, cibleBloc, voisins)
    {	
        cibleInput.click(function()
        {
            if($(this).is(':checked'))
            {
                if(voisins){
                    voisins.hide();
                }
                cibleBloc.toggle();
            }else{
                cibleBloc.toggle();
            }
        });
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
    	var traitement = function(input, blocs) {
    		if(input.is(':checked'))
            {
        	   for (bloc in blocs) {
        		   if ($(blocs[bloc]).exists()) {
            		   var values = $(blocs[bloc]).attr('data-condition-value').split('|');
            		   for(key in values) {
            			   if (values[key] == input.val()) {
            				   $(blocs[bloc]).show();
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
          	   $(this).parents('ul').find('input').each(function() {
	        	   traitement($(this), blocs);
          	   });
      	   } else {
      		   traitement($(this), blocs);
      	   }
        });
	}
	
	var initConditions = function()
	{
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
	    if($('#vrac_marche #original input:checked').length == 0)
	        $('#vrac_marche #original input[value="1"]').attr('checked','checked');
	    if($('#vrac_marche #type_transaction input:checked').length == 0)
	        $('#vrac_marche #type_transaction input[value="vin_vrac"]').attr('checked','checked');       
	   updatePanelsAndUnitLabels();    
	   $('#vrac_marche #type_transaction input').click(updatePanelsAndUnitLabels);
	   
	    if($('.vrac_soussigne').exists())
	    {
	        var parentBloc = $('.popup_form');
	        var parentInputs = parentBloc.find('.contenu_onglet:has(.radio_list)');
	        var blocAdresse = parentBloc.find('.adresse_livraison');
	        var blocVendeurAcheteur = parentBloc.find('.vrac_vendeur_acheteur');

	        parentInputs.each(function()
	        {
	            var compteur = 0;
	            var $this = $(this);
	            var cible = $this.attr('data-cible');
	            var listRadio = $this.find('.radio_list');
	            var labelInput = listRadio.find('input:radio');
	
	            labelInput.each(function()
	            {
	                var $thisInput = $(this);
	                var thisIndex = $thisInput.index();
	                var thisId = $thisInput.attr('id');
	                var thisLabel = listRadio.find('label[for='+thisId+']');
	                var cibles = $('.'+cible+'');
	                var eqCible = cibles.eq(compteur);

	                if($thisInput.is(':checked'))
	                {
	                    eqCible.show();
	                }else{
    					resetEtablissement(eqCible, true);
	                	eqCible.hide(); 
	                }
	                
	                checkUncheck($thisInput, eqCible, cibles);
	
	                compteur++;
	            });
	        });
	
	        blocVendeurAcheteur.each(function(){
	            var $this = $(this);
	            var inputReset = $this.find('.modif_info :radio');
	            var blocPourReset = $this.find('.bloc_form:first');
	            inputReset.change(function(){
	                blocPourReset.find(':input').removeAttr('checked');
	            });
	        });
	
	        blocAdresse.each(function()
	        {
	            var $this = $(this);
	            var infos = $this.find('.bloc_form');
	            var blocCheck = $this.find('.section_label_strong :checkbox');
	
	            checkUncheck(blocCheck, infos, infos);
		
				if(!blocCheck.is(':checked')) {
	            	infos.hide();
	        	}
	        });
	    };
	
	    if($('.vrac_marche').exists())
	    {
	
	        var sommeEuros = function(val1, total, cotisation, val3, val4, hasCotisationCvo)
	        {
	            val1.keyup(function()
	            {
	                var thisVal = parseFloat($(this).val());
	                var tauxRepartition = parseFloat(val3.val());
	                var tauxCVO = parseFloat(val4.val());
	
	                if(!isNaN(thisVal))
	                {
	                    if (hasCotisationCvo != 0) {
	                		var cvo = parseFloat((tauxRepartition * tauxCVO).toFixed(2));
	                		var t = thisVal + cvo;
	                		if (!isNaN(t)) {
	                			total.val(t); 
	                		}
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
	        sommeEuros(prix, totalSomme, cotisation, tauxRepartition, tauxCVO, hasCotisationCvo);
	    }

	    if($('.vrac_condition').exists())
	    {
	    	checkUncheck($('#vrac_echeancier_paiements').find('input:radio'), $('#vrac_paiements'), null);
	    }
	
	   var tableau = $('#vrac_soussigne').find('table');
	   var listStyleTableau = $('#recap_saisie').find('ol li');
	
	   listStyleTableau.find('li:even').addClass('impair');
	   tableau.find('tr:even').addClass('impair');
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
	        var bloc = $($(this).attr('data-container')).append($($(this).attr('data-template')).html().replace(regexp_replace, UUID.generate()));
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
	}

	var resetEtablissement = function (bloc, resetFamille) {
		var select = bloc.find('.etablissement_choice select');
		var input_autcomplete = bloc.find('.etablissement_choice input');
		var etablissement_informations = bloc.find('.etablissement_informations');

		if(resetFamille) {
			var inputs_famille = bloc.find('.etablissement_famille_choice input');
			inputs_famille.removeAttr('checked');
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
		ajaxifyProductAutocomplete('#listener_product', '#template_url_product');
	}
	
	var ajaxifyAutocomplete = function(listenerChoice, type, templateUrl)
	{
		var select = $(listenerChoice+' select');
		var input = $(listenerChoice+' input');
		input.live( "autocompleteselect", function(event, ui) {
			var url = $(templateUrl).text().replace(/var---etablissement---/g, $(ui.item.option).val());
			var url = url.replace(/var---type---/g, type);
			$.get(url, function(data){
				$('#etablissement_'+type).html(data);
			});
		});
	}
	
	var ajaxifyProductAutocomplete = function(listenerChoice, templateUrl)
	{
		var select = $(listenerChoice+' select');
		var input = $(listenerChoice+' input');
		input.live( "autocompleteselect", function(event, ui) {
			var hash = $(ui.item.option).val();
			hash = hash.replace(/\//g, "-");
			var url = $(templateUrl).text().replace(/var---product---/g, hash);
			$.get(url, function(data){
				$('#vrac_marche_part_cvo').val(data);
		        var tauxRepartition = parseFloat($('#vrac_marche_repartition_cvo_acheteur').val());
		        var tauxCvo = parseFloat(data);
		        var total = 0;
		        if (!isNaN(tauxCvo * tauxRepartition))
		        	total = (tauxCvo * tauxRepartition).toFixed(2);
				$('#vrac_cotisation_interpro').text(total);
				
				var sommeEuros = function(val1, total, cotisation, val3, val4, hasCotisationCvo)
		        {
		                var thisVal = parseFloat(val1.val());
		                var tauxRepartition = parseFloat(val3.val());
		                var tauxCVO = parseFloat(val4.val());
		
		                if(!isNaN(thisVal))
		                {
		                    if (hasCotisationCvo != 0) {
		                		var cvo = parseFloat((tauxRepartition * tauxCVO).toFixed(2));
		                		var t = thisVal + cvo;
		                		if (!isNaN(t)) {
									console.log(t);
		                			total.val(t); 
		                		}
		                	}
		                } else {
							console.log('fuck');
		                }
		        }
		        var prix = $('#vrac_marche_prix_unitaire');
		        var totalSomme = $('#vrac_marche_prix_total_unitaire');
		        var hasCotisationCvo = $('#vrac_marche_has_cotisation_cvo').val();
		        var cotisation = $('#vrac_cotisation_interpro');
		        var tauxRepartition = $('#vrac_marche_repartition_cvo_acheteur');
		        var tauxCVO = $('#vrac_marche_part_cvo');
		        sommeEuros(prix, totalSomme, cotisation, tauxRepartition, tauxCVO, hasCotisationCvo);
				
			});
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
    }

	$(document).ready(function()
	{
		 $(this).initBlocCondition();
	     initMarche();
	     initConditions();
	     initTransactions();
	     initAutoComplete();
	     initEtablissements();
	     initChoicesListener();
	     initProductListener();

	     initCollectionAddTemplate('.btn_ajouter_ligne_template', /var---nbItem---/g, callbackAddTemplate);
	     initCollectionAddTemplate('.btn_ajouter_ligne_template_sub', /var---nbSubItem---/g, callbackAddTemplate);
	     initFamilleEtablissementTemplate('.famille', /var---famille---/g);
	     initCollectionDeleteTemplate();
	});
})(jQuery);