<script id="templateformsOrganisme" type="text/x-jquery-tmpl">
<div class="ligne_form" data-key="${index}">
	<table>
		<tbody>
			<tr>
				<td>
					<span class="error"></span>
					<label for="produit_definition_noeud_organismes_${index}_date">Date: </label>
					<br>
					<input id="produit_definition_noeud_organismes_${index}_date" class="datepicker" type="text" name="produit_definition[noeud_organismes][${index}][date]">
				</td>
				<td>
					<span class="error"></span>
					<label for="produit_definition_noeud_organismes_${index}_oioc">OI/OC: </label>
					<br>
					<input type="text" id="produit_definition_noeud_organismes_${index}_oioc" name="produit_definition[noeud_organismes][${index}][oioc]">
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