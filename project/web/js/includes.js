/**
 * Fichier : includes.js
 * Description : Inclusion de fichiers JS
 * Auteur : Hamza Iqbal - hiqbal[at]actualys.com
 * Copyright: Actualys
 ******************************************/

(function($)
{
	/**
	 * G�re l'inclusion de fichier JS
	 * $.fn.include(chemin, fichier, {condition: false, operateur: '', version: ''});
	 ******************************************/
	$.fn.includeJS = function(chemin, fichier, opt)
	{
		var options =
		{
			condition: false,
			operateur: '',
			version: ''
		};
		
		if(opt) options = $.extend(options, opt);
	
		if(options.condition)
		{
			document.write('\n\t<!--[if '+ options.operateur +' '+ options.version +']><script type="text/javascript" src="' + chemin + fichier + '"></scr' + 'ipt><![endif]-->');
		}
		else
		{
			document.write('\n\t<script type="text/javascript" src="' + chemin + fichier + '"></scr' + 'ipt>');
		}
	};
	
	/**
	 * Inclusions
	 ******************************************/
	// Librairies
	$.fn.includeJS(jsPath, 'lib/jquery-ui-1.8.21.min.js');
	
	// Plugins
	$.fn.includeJS(jsPath, 'plugins/selectivizr-min.js', {condition: true, operateur: 'lte', version: 'IE 8'});
	$.fn.includeJS(jsPath, 'plugins/jquery.plugins.min.js');
	$.fn.includeJS(jsPath, 'plugins/ui.dropdownchecklist-1.3-min.js');
	$.fn.includeJS(jsPath, 'plugins/highcharts/highcharts.js');
	$.fn.includeJS(jsPath, 'plugins/highcharts/modules/exporting.js');
		
	// Fonctions personnalisées)
	$.fn.includeJS(jsPath, 'konami.js');
	$.fn.includeJS(jsPath, 'global.js?20190307');
	$.fn.includeJS(jsPath, 'popups.js?20170712');
	$.fn.includeJS(jsPath, 'form.js');
	$.fn.includeJS(jsPath, 'autocomplete.js?20160122');
	$.fn.includeJS(jsPath, 'contrat.js');
	$.fn.includeJS(jsPath, 'drm.js');
	$.fn.includeJS(jsPath, 'declaration.js?20191105');
	$.fn.includeJS(jsPath, 'declaration_acq.js?20190307');
    $.fn.includeJS(jsPath, 'produits.js');
    $.fn.includeJS(jsPath, 'ajaxHelper.js');
    $.fn.includeJS(jsPath, 'vrac.js?20191031');
	$.fn.includeJS(jsPath, 'statistiques.js');

})(jQuery);