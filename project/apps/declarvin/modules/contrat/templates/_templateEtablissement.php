<script id="template_etablissement" type="text/x-jquery-tmpl">
	<div id="etablissement${nbEtablissements}" class="etablissement">
		<div class="ligne_form">
			<label for="contrat_etablissements_${nbEtablissements}_raison_sociale">Raison sociale*: </label>
			<input type="text" id="contrat_etablissements_${nbEtablissements}_raison_sociale" name="contrat[etablissements][${nbEtablissements}][raison_sociale]">
		</div>
		<div class="ligne_form">
			<label for="contrat_etablissements_${nbEtablissements}_nom">Nom commercial*: </label>
			<input type="text" id="contrat_etablissements_${nbEtablissements}_nom" name="contrat[etablissements][${nbEtablissements}][nom]">
		</div>
		<div class="ligne_form">
			<label for="contrat_etablissements_${nbEtablissements}_siret_cni">SIRET*: <a href="" class="msg_aide" data-msg="help_popup_mandat_siret" title="Message aide"></a></label>
			<input type="text" id="contrat_etablissements_${nbEtablissements}_siret_cni" name="contrat[etablissements][${nbEtablissements}][siret_cni]">
		</div>
		<a href="#" class="supprimer">Supprimer</a>
	</div>
</script>