<script type="text/javascript">
$(document).ready( function()
{
	$('#addRow').click(function() {
		$(this).hide();
		$.post("<?php echo url_for('drm_mouvements_generaux_add_form') ?>", { nb_produit: $('#nbProduit').val() },
			function(data) {
				$(data).insertAfter($("#monTab tr:last"));
				$('#nbProduit').val(parseInt($('#nbProduit').val()) + 1);
		});
	});
	$('.deleteRow').live('click', function() {
		$(this).parent().parent().remove();
		$('#nbProduit').val(parseInt($('#nbProduit').val()) - 1);
		$('#addRow').show();
	});
	$('.saveRow').live('click', function() {
		$('#addRow').show();
	});
})
</script>
<section id="contenu">
	<?php include_partial('global/navTop', array('active' => 'drm')) ?>
	<div id="creation_compte" style="width:100%;">
		<h1>Déclaration Récapitulative Mensuelle</h1>
		<p>DRM 2011 - MARS</p>
		<br />
		<?php include_partial('drm/etapes', array('active' => 'ajouts-liquidations', 'pourcentage' => '10')) ?>
		<?php include_partial('drm/onglets', array('active' => 'mouvements-generaux')) ?>
		<br />
		<input id="nbProduit" type="hidden" name="nb_produit" value="<?php echo $nbProduit ?>" />
		<div>
			<table id="monTab" width="100%">
				<tr>
					<th>Appellation</th>
					<th>Couleur</th>
					<th>Dénomination</th>
					<th>Label</th>
					<th>Disponible</th>
					<th>Stock vide</th>
					<th>Pas de mouvement</th>
					<th></th>
				</tr>
				<tr>
					<td align="center">Côtes du Rhône</td>
					<td align="center">Rouge</td>
					<td align="center">Dénomination</td>
					<td align="center">Label</td>
					<td align="center">87HL</td>
					<td align="center"><input type="checkbox" checked="checked" disabled="disabled"></td>
					<td align="center"><input type="checkbox" disabled="disabled"></td>
					<td></td>
				</tr>
				<tr>
					<td align="center">Côtes du Rhône</td>
					<td align="center">Rouge</td>
					<td align="center">Dénomination</td>
					<td align="center">Label</td>
					<td align="center">87HL</td>
					<td align="center"><input type="checkbox" disabled="disabled"></td>
					<td align="center"><input type="checkbox" disabled="disabled"></td>
					<td></td>
				</tr>
				<tr>
					<td align="center">Côtes du Rhône</td>
					<td align="center">Rouge</td>
					<td align="center">Dénomination</td>
					<td align="center">Label</td>
					<td align="center">87HL</td>
					<td align="center"><input type="checkbox" disabled="disabled"></td>
					<td align="center"><input type="checkbox" checked="checked" disabled="disabled"></td>
					<td></td>
				</tr>
			</table>
			<a href="javascript:void(0)" id="addRow">Add row</a>
		</div>
		<?php include_partial('global/boutons', array('nextUrl' => url_for('@drm_evolution'), 'prevUrl' => url_for('@drm_informations'))) ?>
	</div>
</section>