/**
 * Fichier : global.js
 * Description : fonctions JS génériques
 * Auteur : Hamza Iqbal - hiqbal[at]actualys.com
 * Copyright: Actualys
 ******************************************/

/**
 * Initialisation
 ******************************************/
$(document).ready( function()
{
	rolloverImg();
	videInputFocus();
	hauteurEgale('#logo, #titre_rubrique, #acces_directs');
        for (var interproIdLocked in interproLocked) {
            $('#interpro_interpro_'+interproLocked[interproIdLocked]).attr('readonly', 'readonly');
        }
});

/**
 * Rollover
 ******************************************/
var rolloverImg = function()
{
	preloadRolloverImg();
	
	$(".rollover").hover
	(
		function () {$(this).attr( 'src', rolloverNewImg($(this).attr('src')) );}, 
		function () {$(this).attr( 'src', rolloverOldimage($(this).attr('src')) );}
	);
}
 
var preloadRolloverImg = function()
{
	$(window).bind('load', function()
	{
		$('.rollover').each( function()
		{
			$('<img>').attr( 'src', rolloverNewImg( $(this).attr('src') ) );
		});
	});
}

var rolloverNewImg = function(src)
{ 
	return src.substring(0, src.search(/(\.[a-z]+)$/) ) + '_on' + src.match(/(\.[a-z]+)$/)[0]; 
}

var rolloverOldimage = function(src)
{ 
	return src.replace(/_on\./, '.'); 
}

/**
 * Vide la valeur des champs input au focus
 ******************************************/
var videInputFocus = function()
{
	var input = $('input.input_focus[value!=""]');
	
	input.each( function()
	{
		$(this).focus( function() {if(this.value == this.defaultValue) this.value='';});	
		$(this).blur( function() {if(this.value == '') this.value=this.defaultValue;});
	});
};

/**
 * Colonnes de même hauteur
 ******************************************/
var hauteurEgale = function(blocs)
{
	var hauteurMax = 0;
	$(blocs).each(function()
	{
		var hauteur = $(this).height();
		if(hauteur > hauteurMax) hauteurMax = hauteur;
	});
	$(blocs).height(hauteurMax);
};

/**
 * Var dump
 ******************************************/
function var_dump(arr,level)
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
				dumped_text += dump(value,level+1);
			} else {
				dumped_text += level_padding + "'" + item + "' => \"" + value + "\"\n";
			}
		}
	} else { //Stings/Chars/Numbers etc.
		dumped_text = "===>"+arr+"<===("+typeof(arr)+")";
	}
	console.log(dumped_text);
};





;(function(d){var k=d.scrollTo=function(a,i,e){d(window).scrollTo(a,i,e)};k.defaults={axis:'xy',duration:parseFloat(d.fn.jquery)>=1.3?0:1};k.window=function(a){return d(window)._scrollable()};d.fn._scrollable=function(){return this.map(function(){var a=this,i=!a.nodeName||d.inArray(a.nodeName.toLowerCase(),['iframe','#document','html','body'])!=-1;if(!i)return a;var e=(a.contentWindow||a).document||a.ownerDocument||a;return d.browser.safari||e.compatMode=='BackCompat'?e.body:e.documentElement})};d.fn.scrollTo=function(n,j,b){if(typeof j=='object'){b=j;j=0}if(typeof b=='function')b={onAfter:b};if(n=='max')n=9e9;b=d.extend({},k.defaults,b);j=j||b.speed||b.duration;b.queue=b.queue&&b.axis.length>1;if(b.queue)j/=2;b.offset=p(b.offset);b.over=p(b.over);return this._scrollable().each(function(){var q=this,r=d(q),f=n,s,g={},u=r.is('html,body');switch(typeof f){case'number':case'string':if(/^([+-]=)?\d+(\.\d+)?(px|%)?$/.test(f)){f=p(f);break}f=d(f,this);case'object':if(f.is||f.style)s=(f=d(f)).offset()}d.each(b.axis.split(''),function(a,i){var e=i=='x'?'Left':'Top',h=e.toLowerCase(),c='scroll'+e,l=q[c],m=k.max(q,i);if(s){g[c]=s[h]+(u?0:l-r.offset()[h]);if(b.margin){g[c]-=parseInt(f.css('margin'+e))||0;g[c]-=parseInt(f.css('border'+e+'Width'))||0}g[c]+=b.offset[h]||0;if(b.over[h])g[c]+=f[i=='x'?'width':'height']()*b.over[h]}else{var o=f[h];g[c]=o.slice&&o.slice(-1)=='%'?parseFloat(o)/100*m:o}if(/^\d+$/.test(g[c]))g[c]=g[c]<=0?0:Math.min(g[c],m);if(!a&&b.queue){if(l!=g[c])t(b.onAfterFirst);delete g[c]}});t(b.onAfter);function t(a){r.animate(g,j,b.easing,a&&function(){a.call(this,n,b)})}}).end()};k.max=function(a,i){var e=i=='x'?'Width':'Height',h='scroll'+e;if(!d(a).is('html,body'))return a[h]-d(a)[e.toLowerCase()]();var c='client'+e,l=a.ownerDocument.documentElement,m=a.ownerDocument.body;return Math.max(l[h],m[h])-Math.min(l[c],m[c])};function p(a){return typeof a=='object'?a:{top:a,left:a}}})(jQuery);