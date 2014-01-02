<script id="templateformsDouane" type="text/x-jquery-tmpl">
<div class="ligne_form" data-key="${index}">
	<table>
		<tbody>
			<tr>
				<td>
					<span class="error"></span>
					<label for="produit_definition_noeud_droits_douane_${index}_date">Date: </label>
					<br>
					<input id="produit_definition_noeud_droits_douane_${index}_date" class="datepicker" type="text" name="produit_definition[noeud_droits_douane][${index}][date]">
				</td>
				<td>
					<span class="error"></span>
					<label for="produit_definition_noeud_droits_douane_${index}_code">Code: </label>
					<br>
					<input type="text" id="produit_definition_noeud_droits_douane_${index}_code" name="produit_definition[noeud_droits_douane][${index}][code]">
				</td>
				<td>
					<span class="error"></span>
					<label for="produit_definition_noeud_droits_douane_${index}_libelle">Libelle: </label>
					<br>
					<input type="text" id="produit_definition_noeud_droits_douane_${index}_libelle" name="produit_definition[noeud_droits_douane][${index}][libelle]">
				</td>
				<td>
					<span class="error"></span>
					<label for="produit_definition_noeud_droits_douane_${index}_taux">Taux: </label>
					<br>
					<input type="text" id="produit_definition_noeud_droits_douane_${index}_taux" class=" num num_float" autocomplete="off" name="produit_definition[noeud_droits_douane][${index}][taux]">
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