/**
 * Fichier : global.js
 * Description : fonctions JS génériques
 * Auteur : Hamza Iqbal - hiqbal[at]actualys.com
 * Copyright: Actualys
 ******************************************/

/**
 * Initialisation
 ******************************************/
(function($)
{
	$(document).ready( function()
	{
		$.metadata.setType('html5');
		$.detectTerminal();
		
		$.inputPlaceholder();
		$('img.rollover').survolImg();
                
		$('.flash_notice').delay(1000).fadeOut(500);
				
		$('.form_delay').submit(function(e)
		{
			var form = this;
			var $form = $(form);
			
			e.preventDefault();
			window.setTimeout( function()
			{
				form.submit();
			}, 500);
		});

               $(".datepicker").datepicker('change',{dayNamesMin: ["Di", "Lu", "Ma", "Me", "Je", "Ve", "Sa"], monthNames: ["Janvier","Février","Mars","Avril","Mai","Juin","Juillet","Aout","Septembre","Octobre","Novembre","Décembre"], dateFormat: 'dd-mm-yy', firstDay:1 }); 
               
	});
	
	
	
	
	/**
	 * Transforme une chaîne de caractères en objet JS
	 * $.detectTerminal();
	 ******************************************/
	$.detectTerminal = function()
	{
		var terminalAgent = navigator.userAgent.toLowerCase();
		var agentID = terminalAgent.match(/(iphone|ipod|ipad|android)/);
		var terminal = '';
		var version;
		
		if(agentID)
		{
			if(agentID.indexOf('iphone') >= 0) terminal = 'iphone';
			else if(agentID.indexOf('ipod') >=0 ) terminal = 'ipod';
			else if(agentID.indexOf('ipad') >= 0) terminal = 'ipad';
			else if(agentID.indexOf('android') >= 0) terminal = 'android';
		}
		else
		{
			version = parseInt($.browser.version);
			
			if($.browser.webkit) terminal = 'webkit';
			else if($.browser.mozilla) terminal = 'mozilla';
			else if($.browser.opera) terminal = 'opera';
			else if($.browser.msie)
			{
				if(version == 6) terminal = 'msie6';
				else if(version == 7) terminal = 'msie7';
				else if(version == 8) terminal = 'msie8';
				else if(version == 9) terminal = 'msie9';
			}
		}
		
		$('body').addClass(terminal);
		return terminal;
	};
	
	
	/**
	 * Gère les raccourcis clavier du type Ctrl+Touche
	 * $.ctrl(key, callback, args);
	 ******************************************/
	$.ctrl = function(key, callback, args)
	{
		$(document).keydown(function(e)
		{
			if(!args) args = [];
			
			if(e.keyCode == key && e.ctrlKey)
			{
				callback.apply(this, args);
				return false;
			}
		});
	};
	/**
	 * Gère le raccourci clavier Echap
	 * $.echap(callback, args);
	 ******************************************/
	$.echap = function(callback, args)
	{
		$(document).keydown(function(e)
		{
			if(!args) args = [];
			
			if(e.keyCode == 27)
			{
				callback.apply(this, args);
				return false;
			}
		});
	};
	
	/**
	 * Gère les raccourcis clavier du type Shift+Touche
	 * $.shift(key, callback, args);
	 ******************************************/
	$.shift = function(key, callback, args)
	{
    	$(document).keydown(function(e)
		{
        	if(!args) args = []; 
			
            if(e.keyCode == key && e.shiftKey)
			{
				callback.apply(this, args);
            	return false;
			}
		});
    };
	
	
	
	/**
	 * Contrôle la bonne saisie de nombres dans
	 * un champ
	 * $(s).saisieNum(float, callbackKeypress);
	 ******************************************/
	$.fn.saisieNum = function(float, callbackKeypress, callbackBlur)
	{
		var champ = $(this);
		
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
				if(touche == 46 && ponctuationPresente) e.preventDefault(); 
				// virgule déjà présente
				if(touche == 44 && ponctuationPresente) e.preventDefault(); 
				// 2 décimales
				if(val.match(/[\.\,][0-9][0-9]/) && chiffre && e.currentTarget && e.currentTarget.selectionStart > val.length - 3) e.preventDefault();
			}
			// Champ nombre entier
			else
			{
				if(touche != 8 && touche != 0 && !chiffre) e.preventDefault();
			}
			
			if(callbackKeypress) callbackKeypress();
			return e;
		});
		
		// A chaque touche pressée
		champ.keyup(function(e)
		{
			var touche = e.which;
			
			// touche "retour"
			if(touche == 8)
			{
				if(callbackKeypress) callbackKeypress(); 
				return e;
			}
		});
		
		
		// A chaque fois que l'on quitte le champ
		champ.blur(function()
		{
			$(this).nettoyageChamps();
			if(callbackBlur) callbackBlur();
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
			if(val.indexOf('.') != -1 && val.length == 1) val = ''; //val = '0';
			
			// Un nombre commençant par 0 peut être interprété comme étant en octal
			if(val.indexOf('0') == 0 && val.length > 1) val = val.substring(1);
			
			// Comparaison nombre entier / flottant
			if(float || parseInt(val) != parseFloat(val)) val = parseFloat(val).toFixed(2);		
			else val = parseInt(val);
		}
		// Si rien n'a été saisi
		//else val = 0;
		else val = '';
		
		// Si ce n'est pas un nombre (ex : copier/coller d'un texte)
		if(isNaN(val)) val = ''; //val = 0;

		/*if (val == 0) {
			champ.addClass('num_light');
		} else {
			champ.removeClass('num_light');
		}*/
		
		champ.attr('value', val);
	};
	
})(jQuery);