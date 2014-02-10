<script id="templateformsCvo" type="text/x-jquery-tmpl">
<div class="ligne_form" data-key="${index}">
	<table>
		<tbody>
			<tr>
				<td>
					<span class="error"></span>
					<label for="produit_definition_noeud_droits_cvo_${index}_date">Date cvo: </label>
					<br>
					<input id="produit_definition_noeud_droits_cvo_${index}_date" class="datepicker" type="text" name="produit_definition[noeud_droits_cvo][${index}][date]">
				</td>
				<td>
					<span class="error"></span>
					<label for="produit_definition_noeud_droits_cvo_${index}_code">Code cvo: </label>
					<br>
					<input type="text" id="produit_definition_noeud_droits_cvo_${index}_code" name="produit_definition[noeud_droits_cvo][${index}][code]">
				</td>
				<td>
					<span class="error"></span>
					<label for="produit_definition_noeud_droits_cvo_${index}_libelle">Libelle cvo: </label>
					<br>
					<input type="text" id="produit_definition_noeud_droits_cvo_${index}_libelle" name="produit_definition[noeud_droits_cvo][${index}][libelle]">
				</td>
				<td>
					<span class="error"></span>
					<label for="produit_definition_noeud_droits_cvo_${index}_taux">Taux cvo: </label>
					<br>
					<input type="text" id="produit_definition_noeud_droits_cvo_${index}_taux" class=" num num_float" autocomplete="off" name="produit_definition[noeud_droits_cvo][${index}][taux]">
				</td>
				<td>
					<br>
					&nbsp;<a class="removeForm btn_suppr" href="#"></a>
				</td>
			</tr>
		</tbody>
	</table>
</div>
</script>