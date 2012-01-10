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
		
		$.videInputFocus();
		$('img.rollover').survolImg();
	});
	
	
	/**
	 * Var dump
	 ******************************************/
	$.varDump = function(arr,level)
	{
		var dumped_text = "";
		if(!level) level = 0;
		
		//The padding given at the beginning of the line.
		var level_padding = "";
		for(var j=0;j<level+1;j++) level_padding += "    ";
		
		if(typeof(arr) == 'object') { //Array/Hashes/Objects 
			for(var item in arr) {
				var value = arr[item];
				
				if(typeof(value) == 'object') { //If it is an array,
					dumped_text += level_padding + "'" + item + "' ...\n";
					dumped_text += $.varDump(value,level+1);
				} else {
					dumped_text += level_padding + "'" + item + "' => \"" + value + "\"\n";
				}
			}
		} else { //Stings/Chars/Numbers etc.
			dumped_text = "===> "+arr+" <=== ("+typeof(arr)+")";
		}
		console.log(dumped_text);
	};
	
	/**
	 * Vérifie si un élément existe
	 * $(s).exists();
	 ******************************************/
	$.fn.exists = function()
	{
        return $(this).size() > 0;
    };
	
	/**
	 * Transforme une chaîne de caractères en objet JS
	 * $.unserialize(s);
	 ******************************************/
	$.unserialize = function(s)
	{
	 	var s = decodeURI(s).split("&");
		var param;
		var paramsTab = {};
		
		$.each(s, function()
		{
			param = this.split("=");
			paramsTab[param[0]] = param[1];
        });
		
        return paramsTab;
    };
	
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
	 * Gère le remplacement d'image au survol
	 * $(s).survolImg({suffixe: '_on'});
	 ******************************************/
	$.fn.survolImg = function(opt)
	{
		var images = $(this);
		var options = { suffixe: '_on' };
		if(opt) options = $.extend(options, opt);
		
		images.prechargeSurvolImg(options.suffixe);
		
		images.hover
		(
			function () {$(this).attr( 'src', survolCheminImg('survol', $(this).attr('src'), options.suffixe) );}, 
			function () {$(this).attr( 'src', survolCheminImg('', $(this).attr('src'), options.suffixe) );}
		);
	};
	 
	$.fn.prechargeSurvolImg = function(suffixe)
	{
		var images = $(this);
		
		$(window).bind('load', function()
		{
			images.each( function()
			{
				$('<img>').attr( 'src', survolCheminImg('survol', $(this).attr('src'), suffixe) );
			});
		});
	}
	
	var survolCheminImg = function(etat, src, suffixe)
	{
		if(etat == 'survol')
			return src.substring(0, src.search(/(\.[a-z]+)$/) ) + suffixe + src.match(/(\.[a-z]+)$/)[0]; 
		else
			return src.replace(suffixe+'.', '.');	
	}
	
	/**
	 * Vide la valeur des champs input au focus
	 * $.videInputFocus();
	 ******************************************/
	$.videInputFocus = function(opt)
	{	
		if(!Modernizr.input.placeholder)
		{
			var input = $('input[placeholder!=""][value=""]');
			
			input.each(function()
			{
				var placeholder = $(this).attr('placeholder');
				$(this).val(placeholder);
				
				$(this).focus( function() { if($(this).val() == placeholder) $(this).val(''); });	
				$(this).blur( function() { if($(this).val() == '') $(this).val(placeholder); });
			});
		}
	};
	
	/**
	 * Rollover d'un élément
	 * $(s).survolElem
		({
			classe: 'hover',
			ie6: false
		});
	 ******************************************/
	$.fn.survolElem = function(opt)
	{
		var elem = $(this);
		
		var options =
		{
			classe: 'hover',
			ie6: false
		};
		
		if(opt) options = $.extend(options, opt);
		
		if(options.ie6 && !($.browser.msie && parseInt($.browser.version) == 6)) return false;
		
		elem.hover
		(
			function () { $(this).addClass(options.classe); }, 
			function () { $(this).removeClass(options.classe); }
		);
	};
	
	/**
	 * Blocs de même hauteur
	 * $(s).hauteurEgale();
	 ******************************************/
	$.fn.hauteurEgale = function()
	{
		var blocs = $(this);
		var hauteurMax = 0;
		
		blocs.height('auto');
		blocs.each(function()
		{
			var hauteur = $(this).height();
			if(hauteur > hauteurMax) hauteurMax = hauteur;
		});
		
		blocs.height(hauteurMax);
	};
	
	/**
	 * Blocs de même largeur
	 * $(s).largeurEgale();
	 ******************************************/
	$.fn.largeurEgale = function()
	{
		var blocs = $(this);
		var largeurMax = 0;
		
		blocs.each(function()
		{
			var largeur = $(this).width();
			if(largeur > largeurMax) largeurMax = largeur;
		});
		
		blocs.width(largeurMax);
	};
	
	
	/**
	 * Affiche / Masque plus de texte
	 * $(s).voirPlus
		({
			contenu: '.plus',
			lien: '.voir_plus',
			lien_ouvert: '.voir_plus_on',
			vitesse: '',
			callback: function() {}
		});
	 ******************************************/
	$.fn.voirPlus = function(opt) 
	{
		var blocs = $(this);
		
		var options =
		{
			contenu: '.plus',
			lien: '.voir_plus',
			lien_ouvert: '.voir_plus_on',
			vitesse: '',
			callback: function() {}
		};
		
		if(opt) options = $.extend(options, opt);
		
		blocs.each(function()
		{
			var bloc = $(this);
			var contenu = bloc.find('.'+options.contenu);
			var lien = bloc.find('a.'+options.lien);
	
			contenu.hide();
			
			lien.click(function()
			{
				contenu.slideToggle(options.vitesse, function(){ options.callback(); });
				lien.toggleClass(options.lien_ouvert);
				return false;
			});
		});
	}
	
	
	/**
	 * Retourne les paramètres d'une URL
	 * URL de la page par défaut
	 * $.getURLParam(url);
	 ******************************************/
	$.getURLParam = function(url) 
	{
		if(!url) url = window.location.href;
		
		var paramsTab = {};
		var params, param;
		var domaine = false;
		
		// Si c'est une url sans paramètre
		if(url.indexOf('?') == -1 && url.indexOf('=') == -1)
		{
			paramsTab['domaine'] = url;
		}
		else
		{
			url = url.split('?');
			
			// Si un nom de domaine est défini 
			if(url[1])
			{
				domaine = true;
				params = url[1];
			}
			// S'il n'y a que des paramètres
			else if(url[0] != "" && url[0].indexOf('=') != -1)
			{
				params = url[0];
			}
			else domaine = true;

			// Construction de l'objet
			if(params) paramsTab = $.unserialize(params);
			if(domaine) paramsTab['domaine'] = url[0];
		}
		
		return paramsTab;
	};
	
	/**
	 * Met à jour une URL avec des nouveaux 
	 * paramètres
	 * $.setURLParam(url, {nom1: val1, nom2: val2, ... });
	 ******************************************/
	$.setURLParam = function(url, nouvParams) 
	{
		var paramsTab = $.getURLParam(url);
		var nouvUrl = '';
		var domaine = false;
		var param;
		var premierParam = true;
		
		// fusionne les anciens et les nouveaux paramètres
		$.extend(paramsTab, nouvParams);
		
		for(param in paramsTab)
		{
			// Si un nom de domaine est défini
			if(param == 'domaine') domaine = paramsTab[param];
			
			// Sinon construction de l'URL avec les paramètres
			else
			{
				// ajoute '&' avant chaque paramètre sauf le 1er 
				if(!premierParam) nouvUrl += '&';
				premierParam = false;
				
				nouvUrl += param + '=' + paramsTab[param];
			}
		}
		// ajoute le domaine aux paramètres
		if(domaine) nouvUrl = domaine + '?' + nouvUrl;

		return nouvUrl;
	};
	
	/**
	 * Gère les raccourcis clavier du type Ctrl+Touche
	 * $.ctrl(key, callback, args);
	 ******************************************/
	$.ctrl = function(key, callback, args)
	{
    	$(document).keydown(function(e)
		{
        	if(!args) args = []; // IE barks when args is null
			//if(e.keyCode == key.charCodeAt(0) && e.ctrlKey) callback.apply(this, args);
            if(e.keyCode == key && e.ctrlKey)
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
			
			if(callbackKeypress) callbackKeypress();
			return e;
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
	
})(jQuery);