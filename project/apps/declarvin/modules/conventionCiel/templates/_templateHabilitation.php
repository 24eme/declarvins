<script id="template_etablissement" type="text/x-jquery-tmpl">
	<div id="etablissement${nbEtablissements}" class="etablissement">
<div class="ligne_form">
	<label for="convention_ciel_habilitations_${nbEtablissements}_no_accises">Numéro
		accises*: </label> <input type="text"
		id="convention_ciel_habilitations_${nbEtablissements}_no_accises"
		name="convention_ciel[habilitations][${nbEtablissements}][no_accises]">
</div>
<div class="ligne_form">
	<label for="convention_ciel_habilitations_${nbEtablissements}_nom">Nom*: </label> <input
		type="text" id="convention_ciel_habilitations_${nbEtablissements}_nom"
		name="convention_ciel[habilitations][${nbEtablissements}][nom]">
</div>
<div class="ligne_form">
	<label for="convention_ciel_habilitations_${nbEtablissements}_prenom">Prénom*: </label> <input
		type="text" id="convention_ciel_habilitations_${nbEtablissements}_prenom"
		name="convention_ciel[habilitations][${nbEtablissements}][prenom]">
</div>
<div class="ligne_form">
	<label for="convention_ciel_habilitations_${nbEtablissements}_identifiant">Identifiant
		Prodou@ne*: </label> <input type="text"
		id="convention_ciel_habilitations_${nbEtablissements}_identifiant"
		name="convention_ciel[habilitations][${nbEtablissements}][identifiant]">
</div>
<div class="ligne_form">
	<label for="convention_ciel_habilitations_${nbEtablissements}_droit_teleprocedure">Habilitation
		téléprocédure CIEL: </label> <select
		id="convention_ciel_habilitations_${nbEtablissements}_droit_teleprocedure"
		name="convention_ciel[habilitations][${nbEtablissements}][droit_teleprocedure]">
		<option selected="selected" value="">Non habilité</option>
		<option value="consulter">Habilité à consulter</option>
		<option value="gérer">Habilité à gérer</option>
	</select>
</div>
<div class="ligne_form">
	<label for="convention_ciel_habilitations_${nbEtablissements}_droit_telepaiement">Habilitation
		télépaiement CIEL: </label> <select
		id="convention_ciel_habilitations_${nbEtablissements}_droit_telepaiement"
		name="convention_ciel[habilitations][${nbEtablissements}][droit_telepaiement]">
		<option selected="selected" value="">Non habilité</option>
		<option value="adhérer">Habilité à adhérer</option>
		<option value="valider">Habilité à valider</option>
	</select>
</div>
<div class="ligne_form">
	<label for="convention_ciel_habilitations_${nbEtablissements}_mensualisation">Echéance
		mensuelle:</label> <input type="checkbox"
		id="convention_ciel_habilitations_${nbEtablissements}_mensualisation"
		name="convention_ciel[habilitations][${nbEtablissements}][mensualisation]">
</div>
<a href="#" class="supprimer">Supprimer</a>
	</div>
</script>