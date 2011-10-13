$(document).ready( function()
{
	var bool = window.familles || 0;
	if(bool) {
		famillesJSON = JSON.parse(familles);
		if ($("#contratetablissement_famille").val()) {
			var sousFamilles = famillesJSON[$("#contratetablissement_famille").val()];
			var options = '';
		    for (var i in sousFamilles)
		    {
		    	if (sousFamilleSelected == sousFamilles[i]) {
		    		options += '<option value="'+sousFamilles[i]+'" selected="selected">'+sousFamilles[i]+'</option>';
		    	} else {
		    		options += '<option value="'+sousFamilles[i]+'">'+sousFamilles[i]+'</option>';
		    	}
		    }
		    $("#contratetablissement_sous_famille").html(options);
		}
		$("#contratetablissement_famille").change(function(){
			var sousFamilles = famillesJSON[$(this).val()];
			var options = '';
		    for (var i in sousFamilles)
		    {
		    	options += '<option value="'+sousFamilles[i]+'">'+sousFamilles[i]+'</option>';
		    }
		    $("#contratetablissement_sous_famille").html(options);
		});
	}
});
function addEtablissement(html) {
	var nbEtablissements = parseInt($("#contrat_nb_etablissement").val());
	var tabEtablissements = $("#etablissements");
	var etablissement = 
		"" +
		"<div id=\"etablissement"+nbEtablissements+"\">" +
		"			<div class=\"ligne_form\">" +
		"				<label for=\"contrat_etablissements_"+nbEtablissements+"_raison_sociale\">Raison sociale* :</label>" +
		"				<input type=\"text\" id=\"contrat_etablissements_"+nbEtablissements+"_raison_sociale\" name=\"contrat[etablissements]["+nbEtablissements+"][raison_sociale]\">" +
		"			</div>" +
		"			<div class=\"ligne_form\">" +
		"				<label for=\"contrat_etablissements_"+nbEtablissements+"_siret\">SIRET/CNI* :</label>" +
		"				<input type=\"text\" id=\"contrat_etablissements_"+nbEtablissements+"_siret\" name=\"contrat[etablissements]["+nbEtablissements+"][siret_cni]\">" +
		"			</div>" +
		"			<div id=\"optionsEtablissement"+nbEtablissements+"\">" +
		"				<a href=\"javascript:removeEtablissement("+nbEtablissements+")\">Supprimer</a>" +
		"			</div>" +
		"</div>";
	$("#etablissements").append(etablissement);
	$("#contrat_nb_etablissement").val(nbEtablissements + 1);
	$("#addEtablissement").css('display', 'inline-block');
}
function removeEtablissement(ind) {
	var nbEtablissements = parseInt($("#contrat_nb_etablissement").val());
	$("#etablissement"+ind).remove();
	$("#optionsEtablissement"+ind).remove();
	$("#contrat_nb_etablissement").val(nbEtablissements - 1);
	if ((nbEtablissements - 1) == 1) {
		$("#addEtablissement").css('display', 'none');
		$("#r2").attr("checked", "checked");
		$("#r1").removeAttr("checked");
	}
	
}
function removeAllEtablissement() {
	var nbEtablissements = parseInt($("#contrat_nb_etablissement").val());
	for(i=(nbEtablissements-1); i>0; i--) {
		$("#etablissement"+i).remove();
		$("#optionsEtablissement"+i).remove();
	}
	$("#contrat_nb_etablissement").val(1);
	$("#addEtablissement").css('display', 'none');
}
function voirFormAdresseComptabilite() {
	$("#adresseComptabilite").css("display", "block");
}
function masquerFormAdresseComptabilite() {
	$("#adresseComptabilite").css("display", "none");
	 $("#adresseComptabilite input").each(function(){
	   $(this).val('');
	 });
}